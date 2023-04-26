<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Itu :attribute harus diterima.',
    'active_url'           => 'Itu :attribute bukan URL yang valid.',
    'after'                => 'Itu :attribute harus tanggal :date.',
    'after_or_equal'       => 'Itu :attribute harus tanggal setelah atau sama  :date.',
    'alpha'                => 'Itu :attribute mungkin hanya berisi huruf.',
    'alpha_dash'           => 'Itu :attribute hanya boleh berisi huruf, angka, dan tanda hubung.',
    'alpha_num'            => 'Itu :attribute mungkin hanya berisi huruf dan angka.',
    'array'                => 'Itu :attribute harus berupa array.',
    'before'               => 'Itu :attribute harus ada tanggal sebelumnya :date.',
    'before_or_equal'      => 'Itu :attribute harus tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => 'Itu :attribute harus antara :min dan :max.',
        'file'    => 'Itu :attribute harus antara :min dan :max kilobyte.',
        'string'  => 'Itu :attribute harus antara :min dan :max karakter.',
        'array'   => 'Itu :attribute harus antara :min dan :max item.',
    ],
    'boolean'              => 'Itu :attribute lapangan harus benar atau salah.',
    'confirmed'            => 'Itu :attribute konfirmasi tidak cocok.',
    'date'                 => 'Itu :attribute bukan tanggal yang valid.',
    'date_format'          => 'Itu :attribute tidak cocok dengan format :format.',
    'different'            => 'Itu :attribute dan :other harus berbeda.',
    'digits'               => 'Itu :attribute harus :digits digit.',
    'digits_between'       => 'Itu :attribute harus antara :min dnd :max digit.',
    'dimensions'           => 'Itu :attribute memiliki dimensi gambar yang tidak valid.',
    'distinct'             => 'Itu :attribute lapangan memiliki nilai duplikat.',
    'email'                => 'Itu :attribute Harus alamat e-mail yang valid.',
    'exists'               => 'Itu selected :attribute tidak valid.',
    'file'                 => 'Itu :attribute harus berupa file.',
    'filled'               => 'Itu :attribute lapangan harus memiliki nilai.',
    'image'                => 'Itu :attribute Pasti gambar.',
    'in'                   => 'Itu terpilih :attribute tidak valid.',
    'in_array'             => 'Itu :attribute lapangan tidak ada :other.',
    'integer'              => 'Itu :attribute harus berupa bilangan bulat.',
    'ip'                   => 'Itu :attribute harus alamat IP yang valid.',
    'ipv4'                 => 'Itu :attribute harus alamat IPv4 yang valid.',
    'ipv6'                 => 'Itu :attribute harus alamat IPv6 yang valid.',
    'json'                 => 'Itu :attribute harus berupa string JSON yang valid.',
    'max'                  => [
        'numeric' => 'Itu :attribute mungkin tidak lebih dari :max.',
        'file'    => 'Itu :attribute mungkin tidak lebih dari :max kilobyte.',
        'string'  => 'Itu :attribute mungkin tidak lebih dari :max karakter.',
        'array'   => 'Itu :attribute mungkin tidak lebih dari :max item.',
    ],
    'mimes'                => 'Itu :attribute harus berupa file type: :values.',
    'mimetypes'            => 'Itu :attribute harus berupa file type: :values.',
    'min'                  => [
        'numeric' => 'Itu :attribute setidaknya harus ada :min.',
        'file'    => 'Itu :attribute setidaknya harus ada :min kilobyte.',
        'string'  => 'Itu :attribute setidaknya harus ada :min karakter.',
        'array'   => 'Itu :attribute setidaknya harus ada :min item.',
    ],
    'not_in'               => 'Itu selected :attribute tidak valid.',
    'numeric'              => 'Itu :attribute harus nomor.',
    'present'              => 'Itu :attribute lapangan harus ada.',
    'regex'                => 'Itu :attribute format tidak valid.',
    'required'             => 'Itu :attribute lapangan diperlukan.',
    'required_if'          => 'Itu :attribute lapangan diperlukan saat :other aku s :value.',
    'required_unless'      => 'Itu :attribute bidang diperlukan kecuali :other aku s di :values.',
    'required_with'        => 'Itu :attribute lapangan diperlukan saat :values aku s menyajikan.',
    'required_with_all'    => 'Itu :attribute lapangan diperlukan saat :values aku s menyajikan.',
    'required_without'     => 'Itu :attribute lapangan diperlukan saat :values aku s tidak menyajikan.',
    'required_without_all' => 'Itu :attribute lapangan diperlukan saat tidak ada :values hadir.',
    'same'                 => 'Itu :attribute dan :other harus cocok.',
    'size'                 => [
        'numeric' => 'Itu :attribute harus mengandung :size.',
        'file'    => 'Itu :attribute harus mengandung :size kilobyte.',
        'string'  => 'Itu :attribute harus mengandung :size karakter.',
        'array'   => 'Itu :attribute harus mengandung :size item.',
    ],
    'string'               => 'Itu :attribute harus berupa string.',
    'timezone'             => 'Itu :attribute harus menjadi zona yang valid.',
    'unique'               => 'Itu :attribute sudah diambil.',
    'uploaded'             => 'Itu :attribute gagal diunggah.',
    'url'                  => 'Itu :attribute format tidak valid.',

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
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => config('field.in')

];
