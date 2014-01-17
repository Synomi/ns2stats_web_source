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
            array('height, width, data', 'required'),
            array('height', 'numerical',
                'integerOnly' => true,
                'min' => 1,
                'max' => 400,
                'tooSmall' => 'Minimum image height is 1',
                'tooBig' => 'Maximum image height is 400'),
            array('width', 'numerical',
                'integerOnly' => true,
                'min' => 1,
                'max' => 800,
                'tooSmall' => 'Minimum image width/height is 1',
                'tooBig' => 'Maximum image width is 800'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'heigth' => 'Heigth',
            'width' => 'Width',
            'data' => 'Values',
            'background_image' => 'Background image (optional)'
        );
    }

}
