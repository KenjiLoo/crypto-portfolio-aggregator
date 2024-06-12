<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => '必须接受 :attribute。',
    'accepted_if' => '当 :other 为 :value 时，必须接受 :attribute。',
    'active_url' => ':attribute 不是有效的网址。',
    'after' => ':attribute 在 :date 之后必须是一个日期。',
    'after_or_equal' => ':attribute 必须是在 :date 之后或等于 :date 的日期。',
    'alpha' => ':attribute 必须只包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、数字、破折号和下划线。',
    'alpha_num' => ':attribute 必须只包含字母和数字。',
    'array' => ':attribute 必须是一个数组。',
    'before' => ':attribute 必须是在 :date 的日期。',
    'before_or_equal' => ':attribute 必须是在 :date 之前或等于 :date 的日期。',
    'between' => [
        'numeric' => ':attribute 必须在 :min 和 :max 之间。',
        'file' => ':attribute 必须在 :min 和 :max 千字节之间。',
        'string' => ':attribute 必须在 :min 和 :max 字数之间。',
        'array' => ':attribute 必须在 :min 和 :max 项目之间。',
    ],
    'boolean' => ':attribute 字段必须为 true 或 false。',
    'confirmed' => ':attribute 确认不匹配。',
    'current_password' => '密码错误。',
    'date' => ':attribute 不是有效日期。',
    'date_equals' => ':attribute 必须是等于 :date 的日期。',
    'date_format' => ':attribute 不符合 :format 的格式。',
    'declined' => ':attribute 必须拒绝。',
    'declined_if' => ':attribute 必须被拒绝当 :other 是 :value。',
    'different' => ':attribute 和 :other 必须不同。',
    'digits' => ':attribute 必须是 :digits 位数。',
    'digits_between' => ':attribute 必须在 :min 和 :max 位数之间.',
    'dimensions' => ':attribute 的图像尺寸无效。',
    'distinct' => ':attribute 字段有重复值。',
    'email' => ':attribute 必须是有效的电子邮件地址。',
    'ends_with' => ':attribute 必须以下列之一结束：:values.',
    'enum' => '所选 :attribute 无效。',
    'exists' => '所选 :attribute 无效。',
    'file' => ':attribute 必须是一个文件。',
    'filled' => ':attribute 字段必须有一个值。',
    'gt' => [
        'numeric' => ':attribute 必须大于 :value。',
        'file' => ':attribute must 必须大于 :value 千字节。',
        'string' => ':attribute 必须多于 :value 字数。',
        'array' => ':attribute 必须多于 :value 项目。',
    ],
    'gte' => [
        'numeric' => ':attribute 必须大于或等于 :value。',
        'file' => ':attribute 必须大于或等于 :value 千字节。',
        'string' => ':attribute 必须多于或等于 :value 字数。',
        'array' => ':attribute 必须多于或等于 :value 项目。',
    ],
    'image' => ':attribute 必须是图像。',
    'in' => '所选 :attribute 是无效的。',
    'in_array' => ':attribute 字段不存在于 :other。',
    'integer' => ':attribute 必须是整数。',
    'ip' => ':attribute 必须是有效的 IP 地址。',
    'ipv4' => ':attribute 必须是有效的 IPv4 地址。',
    'ipv6' => ':attribute 必须是有效的 IPv6 地址。',
    'json' => ':attribute 必须是有效的 JSON 字符串。',
    'lt' => [
        'numeric' => ':attribute 必须小于 :value。',
        'file' => ':attribute 必须小于 :value 千字节。',
        'string' => ':attribute 必须少于 :value 字数。',
        'array' => ':attribute 必须少于 :value 项目。',
    ],
    'lte' => [
        'numeric' => ':attribute 必须小于或等于 :value。',
        'file' => ':attribute 必须小于或等于 :value 千字节。',
        'string' => ':attribute 必须少于或等于 :value 字数。',
        'array' => ':attribute 必须少于或等于 :value 项目。',
    ],
    'mac_address' => ':attribute 必须是有效的 MAC 地址。',
    'max' => [
        'numeric' => ':attribute 必须不大于 :max。',
        'file' => ':attribute 必须不大于 :max 千字节。',
        'string' => ':attribute 必须不多于 :max 字数。',
        'array' => ':attribute 必须不多于 :max 项目。',
    ],
    'mimes' => ':attribute 必须是类型为: :values 的文件。',
    'mimetypes' => ':attribute 必须是类型为: :values 的文件。',
    'min' => [
        'numeric' => ':attribute 必须至少为 :min。',
        'file' => ':attribute 必须至少为 :min 千字节。',
        'string' => ':attribute 必须至少为 :min 字数。',
        'array' => ':attribute 必须至少为 :min 项目。',
    ],
    'multiple_of' => ':attribute 必须是 :value 的倍数。',
    'not_in' => '所选 :attribute 是无效的。',
    'not_regex' => ':attribute 格式无效。',
    'numeric' => ':attribute 必须是一个数字。',
    'password' => '密码错误。',
    'present' => ':attribute 字段必须存在。',
    'prohibited' => ':attribute 字段禁止使用。',
    'prohibited_if' => ':attribute 字段当 :other 属于 :value 时禁止使用',
    'prohibited_unless' => '除非 :values 中包含 :other，否则禁止使用 :attribute 字段。',
    'prohibits' => ':attribute 字段中禁止出现 :other。',
    'regex' => ':attribute 格式无效。',
    'required' => ':attribute 字段是必填字段。',
    'required_array_keys' => ':attribute 字段必须包含以下条目： :values。',
    'required_if' => '当 :other 属于 :value， :attribute field 是必填的。',
    'required_unless' => '除非 :values 中包含 :other，否则 :attribute 字段为必填字段。',
    'required_with' => '出现 :values 时，必须使用 :attribute 字段。',
    'required_with_all' => '如果存在 :values 时，则必须填写 :attribute 字段。',
    'required_without' => ':attribute 字段是必填字段，当 :values 不存在时。',
    'required_without_all' => '如果没有 :values，则必须填写 :attribute 字段。',
    'same' => ':attribute 与 :other 必须符合.',
    'size' => [
        'numeric' => ':attribute 必须为 :size。',
        'file' => ':attribute 必须为 :size 千字节。',
        'string' => ':attribute 必须为 :size 字数。',
        'array' => ':attribute 必须包含 :size 项目。',
    ],
    'starts_with' => ':attribute 必须以下列之一开头： :values。',
    'string' => ':attribute 必须是字符串。',
    'timezone' => ':attribute 必须是有效时区。',
    'unique' => ':attribute 已被占用。',
    'uploaded' => ':attribute 上传失败。',
    'url' => ':attribute 必须是有效的网址。',
    'uuid' => ':attribute 必须是有效的通用唯一识别码。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
