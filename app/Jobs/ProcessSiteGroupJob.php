<?php

namespace App\Jobs;

use App\Models\Module;
use App\Models\{Site, SiteGroup, SiteGroupAdmin, SiteGroupAdminSite};
use App\Models\{SiteProfile, SiteProfileModule};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldQueue, ShouldBeUnique};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\{Cache, DB, Hash, Log};
use Throwable;

class ProcessSiteGroupJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    // public $uniqueFor = 5;

    /**
     * The site_group instance id.
     *
     * @var int
     */
    protected $siteGroupId;

    /**
     * The site_profile instance.
     *
     * @var App\Models\SiteProfile
     */
    protected $siteProfile;

    /**
     * The sites array.
     *
     * @var Array
     */
    protected $sites;

    /**
     * The site_profile default name.
     *
     * @var string
     */
    private $siteProfileDefaultName = 'Owner Profile';

    /**
     * The site_group_admin default name.
     *
     * @var string
     */
    private $siteGroupAdminDefaultName = 'Admin';


    /**
     * Create a new job instance.
     *
     * @param integer $siteGroupId
     *
     * @return void
     */
    public function __construct(int $siteGroupId)
    {
        $this->siteGroupId = $siteGroupId;
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return 'sitegroup_' . $this->siteGroupId;
    }

    /**
     * Get the cache driver for the unique job lock.
     *
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function uniqueVia()
    {
        return Cache::driver('redis');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::beginTransaction();

            $siteGroup = SiteGroup::where('id', $this->siteGroupId)->first();

            if ($this->handleSiteProfile($siteGroup)) {
                if ($this->handleSiteProfileModule()) {
                    if ($this->handleSiteModule($siteGroup)) {
                        if ($this->handleSiteGroupAdmin($siteGroup)) {
                            DB::commit();
                            return;
                        }
                    }
                }
            }

            DB::rollBack();
        } catch (\PDOException $err) {
            DB::rollBack();
            Log::error('[' . __CLASS__ . ' - ' . __FUNCTION__ . '] ' . 'err: ' . json_encode($err));
        }

        return;
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     *
     * @return void
     */
    public function failed(Throwable $exception)
    {
        Log::debug('[' . __CLASS__ . ' - ' . __FUNCTION__ . '] ' . 'failed: ' . json_encode($exception));
    }

    /**
     * Site profile handler.
     *
     * @return boolean
     */
    public function handleSiteProfile($siteGroup)
    {

        $siteProfile = new SiteProfile();
        $siteProfile->name = $siteGroup->name . ' '  . $this->siteProfileDefaultName;
        $siteProfile->site_group_id = $this->siteGroupId;
        $siteProfile->is_superadmin = true;

        if ($siteProfile->save()) {
            Log::notice('[' . __CLASS__ . ' - ' . __FUNCTION__ . '] ' . 'Site profile successfully created.');
            $this->siteProfile = $siteProfile;
            return true;
        }

        return false;
    }

    /**
     * Site profile module handler.
     *
     * @return boolean
     */
    public function handleSiteProfileModule()
    {
        $module = new Module();

        $sgModuleIds = $module::where([
            ['type', 'site_group']
        ])->get()->pluck('id')->toArray();
        Log::debug('[' . __CLASS__ . ' - ' . __FUNCTION__ . '] ' . 'sgModuleIds: ' . json_encode($sgModuleIds));

        if (count($sgModuleIds) > 0) {
            $records = [];

            foreach ($sgModuleIds as $key => $id) {
                $records[$key] = [
                    'site_profile_id' => $this->siteProfile->id,
                    'module_id' => $id
                ];
            }

            return SiteProfileModule::insert($records);
        }

        return false;
    }

    /**
     * Site module handler.
     *
     * @return boolean
     */
    public function handleSiteModule($siteGroup)
    {
        $currencies = config('custom.default_sites');
        $usd_rates = config('custom.default_usd_rates');

        foreach ($currencies as $currency) {
            $siteInsertedId = Site::insertGetId([
                'site_group_id' => $this->siteGroupId,
                'name'          => strtoupper($siteGroup->name) . ' ' . strtoupper(substr_replace($currency, '', 2, 1)),
                'site_code'     => $siteGroup->name . $currency,
                'company_code'  => $siteGroup->company_code,
                'currency'      => $currency,
                'usd_rate'      => $usd_rates[$currency],
                'status'        => 'ACTIVE'
            ]);

            if (!$siteInsertedId) {
                return false;
            } else {
                $this->sites[] = $siteInsertedId;
            }
        }

        Log::notice('[' . __CLASS__ . ' - ' . __FUNCTION__ . '] ' . 'Sites successfully created.');
        return true;
    }

    /**
     * Site group admin handler.
     *
     * @return boolean
     */
    public function handleSiteGroupAdmin($siteGroup)
    {
        $siteGroupAdmin = new SiteGroupAdmin();
        $prefix = strtolower(str_replace(' ', '', $siteGroup->name));

        $adminInsertedId = SiteGroupAdmin::insertGetId([
            'site_group_id'   => $this->siteGroupId,
            'site_profile_id' => $this->siteProfile->id,
            'username'        => strtolower($prefix . '_admin'),
            'password'        => Hash::make('Testing.123!'),
            'name'            => $siteGroup->name . $this->siteGroupAdminDefaultName,
            'is_master_account' => true,
            'status'          => $siteGroupAdmin::STATUS_ACTIVE,
        ]);

        if (!$adminInsertedId) {
            return false;
        } else {
            foreach ($this->sites as $siteId) {
                $adminSiteInserted = SiteGroupAdminSite::insert([
                    'site_group_admin_id' => $adminInsertedId,
                    'site_id'             => $siteId
                ]);

                if (!$adminSiteInserted) {
                    return false;
                }
            }
        }

        Log::notice('[' . __CLASS__ . ' - ' . __FUNCTION__ . '] ' . 'Site group admin successfully created.');
        return true;
    }
}
