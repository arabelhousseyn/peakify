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

    'accepted' => 'The :attribute must be accepted.',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute must only contain letters.',
    'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute must only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'array' => 'The :attribute must have between :min and :max items.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'numeric' => 'The :attribute must be between :min and :max.',
        'string' => 'The :attribute must be between :min and :max characters.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :value.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'array' => 'The :attribute must have more than :value items.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'numeric' => 'The :attribute must be greater than :value.',
        'string' => 'The :attribute must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'The :attribute must have :value items or more.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'array' => 'The :attribute must have less than :value items.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'numeric' => 'The :attribute must be less than :value.',
        'string' => 'The :attribute must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'The :attribute must not have more than :value items.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'array' => 'The :attribute must not have more than :max items.',
        'file' => 'The :attribute must not be greater than :max kilobytes.',
        'numeric' => 'The :attribute must not be greater than :max.',
        'string' => 'The :attribute must not be greater than :max characters.',
    ],
    'mimes' => 'The :attribute must be a file of type: :value.',
    'mimetypes' => 'The :attribute must be a file of type: :value.',
    'min' => [
        'array' => 'The :attribute must have at least :min items.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'numeric' => 'The :attribute must be at least :min.',
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :value.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_array_keys' => 'The :attribute field must contain entries for: :value.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :value.',
    'required_with' => 'The :attribute field is required when :value is present.',
    'required_with_all' => 'The :attribute field is required when :value are present.',
    'required_without' => 'The :attribute field is required when :value is not present.',
    'required_without_all' => 'The :attribute field is required when none of :value are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'array' => 'The :attribute must contain :size items.',
        'file' => 'The :attribute must be :size kilobytes.',
        'numeric' => 'The :attribute must be :size.',
        'string' => 'The :attribute must be :size characters.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :value.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute must be a valid URL.',
    'uuid' => 'The :attribute must be a valid UUID.',

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
        'username' => [
            'required' => 'Nom d\'utilisateur requis.',
            'max' => 'Nom d\'utilisateur maximum de caractères 255.',
            'unique' => 'Nom d\'utilisateur existe déjà.'
        ],
        'email' => [
            'email' => 'L\'e-mail doit être valide.',
            'required' => 'Email requis.',
            'exists' => 'Email n\'existse pas.',
            'unique' => 'Email existe déjà.'
        ],
        'password' => [
            'required' => 'Mot de passe requis.',
            'confirmed' => 'Mot de passe ne correspond pas.'
        ],
        'has_email' => [
            'required' => 'Erreur dans serveur.',
            'boolean' => 'Erreur dans serveur.',
        ],
        'new_password' => [
            'required' => 'Nouveau mot de passe requis.',
            'confirmed' => 'Mot de passe ne correspond pas.'
        ],
        'full_name' => [
            'required' => 'Nom complet requis.',
            'max' => 'Nom complet maximum de caractères 255.'
        ],
        'phone' => [
            'required' =>'Téléphone requis.',
            'unique' => 'Téléphone existe déjà.',
            'digits' => 'Le téléphone doit avoir 10 chiffres.'
        ],
        'job' => [
            'max' => 'Fonction maximum de caractères 255.'
        ],
        'start_at' => [
            'required' => 'L\'heur du debut requis.',
            'date_format' => 'Erreur.'
        ],
        'end_at' => [
            'required' => 'L\'heur du fin requis.',
            'date_format' => 'Erreur.'
        ],
        'address' => [
            'max' => 'Addresse maximum de caractères 255 .'
        ],
        'city' => [
            'max' => 'Ville maximum de caractères 255 .'
        ],
        'name' => [
            'required' => 'Nom requis.',
            'unique' =>'Nom doit être unique.',
            'max' => 'Nom maximum de caractères 255 .'
        ],
        'category_id' => [
            'required' => 'Catégorie requis.',
            'exists' => 'Catégorie n\'existse pas.',
        ],
        'product_code' => [
            'required' => 'Code produit requis.',
            'max' => 'code produit maximum de caractères 255.',
        ],
        'product_name' => [
            'required' => 'Nom produit requis.',
            'max' => 'Nom produit maximum de caractères 255.',
        ],
        'description' => [
            'max' => 'description produit maximum de caractères 500.',
        ],
        'price' => [
            'required' => 'Prix requis.',
            'numeric' => 'Prix doit être numerique.',
        ],
        'offers' => [
            'required' =>'Offres requis.',
            'array' => 'Erreur veuillez vérifier vos informations',
            '*' => 'Erreur veuillez vérifier vos informations.'
        ],
        'value' => [
            'array' => 'Erreur veuillez vérifier vos informations',
            '*' => 'Erreur veuillez vérifier vos informations.'
        ],
        'option_id' => [
            'required' => 'Option requis.',
            'exists' => 'erreur veuillez réessayer.'
        ],
        'variants' => [
            'required' => 'Variants requis.',
            'array' => 'Erreur veuillez vérifier vos informations',
            '*' => 'Erreur veuillez vérifier vos informations.'
        ],
        'product_id' => [
            'required' => 'erreur veuillez réessayer.',
            'exists' => 'erreur veuillez réessayer.'
        ],
        'city_id' => [
            'required' => 'Ville requis.',
            'exists' => 'Ville n\'existse pas.'
        ]
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
