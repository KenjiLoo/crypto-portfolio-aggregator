<?php

namespace App\Models\Traits;

use Illuminate\Http\Request;

trait Savable
{
    protected $isStoring = false;
    protected $isUpdate = false;

    public function isUpdate($isUpdate = true)
    {
        $this->isUpdate = $isUpdate;
    }

    public function isStoring($isStoring = true)
    {
        $this->isStoring = $isStoring;
    }

    public function fillFromRequest(Request $request, $data = null)
    {
        if (is_null($data)) {
            $data = parse_request_data($request->all());
        }
        
        $this->fill($data);
    }

    public function isDeleteable()
    {
        return true;
    }

    public function onBeforeSave(Request $request, $custom = [])
    {
        
    }

    public function onAfterSave(Request $request, $custom = [])
    {
        //do nth
    }
}
