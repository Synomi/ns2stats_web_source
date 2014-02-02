<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SignatureForm extends CFormModel
{

    public $height;
    public $width;
    public $data;
    public $background_image;
    public $background_image_meta;
    public $border;
    public $logo;
    public $steam_image;
    public $flag;
    public $steam_align;
    public $background_number;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            //array('background_image', 'file', 'types'=>'jpg, png'),
            array('data,logo,steam_image,border,background_number,height,width,flag', 'required'),
            array('height', 'numerical',
                'integerOnly' => true,
                'min' => 1,
                'max' => 300,
                'tooSmall' => 'Minimum image height is 1',
                'tooBig' => 'Maximum image height is 300'),
            array('width', 'numerical',
                'integerOnly' => true,
                'min' => 1,
                'max' => 900,
                'tooSmall' => 'Minimum image width is 1',
                'tooBig' => 'Maximum image width is 900'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'height' => 'Max height (limit 300) (image ratio will persist)',
            'width' => 'Max width (limit 900) (width or height might become lower)',
            'data' => 'Dynamic values (logging out resets to default)',
            'background_image' => 'Background image (optional)',
            'background_number' => 'Select background image (ignored if you select custom bg): ',
            'logo' => 'NS2Stats.com logo',
            'border' => 'Steam image border',
            'steam_image' => 'Your steam image',
            'steam_align' => 'Start dynamic values after steam image (instead of staring from 0x0)',
            'flag' => 'Country flag (requires steam image)'
        );
    }

}
