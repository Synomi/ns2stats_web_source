<?php

/**
 * This is the model class for table "donation".
 *
 * The followings are the available columns in table 'donation':
 * @property integer $id
 * @property string $added
 * @property string $last_name
 * @property string $residence_country
 * @property string $payer_status
 * @property string $txn_id
 * @property string $first_name
 * @property string $item_number
 * @property string $payment_status
 * @property string $mc_fee
 * @property string $mc_gross
 * @property string $custom
 * @property string $ipn_track_id
 * @property string $payer_email
 * @property string $receiver_email
 * @property string $mc_currency
 */
class Donation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'donation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('last_name, first_name, custom, payer_email, receiver_email', 'length', 'max'=>255),
			array('residence_country, payer_status, txn_id, item_number, payment_status, ipn_track_id, mc_currency', 'length', 'max'=>50),
			array('mc_fee, mc_gross', 'length', 'max'=>10),
			array('added', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, added, last_name, residence_country, payer_status, txn_id, first_name, item_number, payment_status, mc_fee, mc_gross, custom, ipn_track_id, payer_email, receiver_email, mc_currency', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'added' => 'Added',
			'last_name' => 'Last Name',
			'residence_country' => 'Residence Country',
			'payer_status' => 'Payer Status',
			'txn_id' => 'Txn',
			'first_name' => 'First Name',
			'item_number' => 'Item Number',
			'payment_status' => 'Payment Status',
			'mc_fee' => 'Mc Fee',
			'mc_gross' => 'Mc Gross',
			'custom' => 'Custom',
			'ipn_track_id' => 'Ipn Track',
			'payer_email' => 'Payer Email',
			'receiver_email' => 'Receiver Email',
			'mc_currency' => 'Mc Currency',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('added',$this->added,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('residence_country',$this->residence_country,true);
		$criteria->compare('payer_status',$this->payer_status,true);
		$criteria->compare('txn_id',$this->txn_id,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('item_number',$this->item_number,true);
		$criteria->compare('payment_status',$this->payment_status,true);
		$criteria->compare('mc_fee',$this->mc_fee,true);
		$criteria->compare('mc_gross',$this->mc_gross,true);
		$criteria->compare('custom',$this->custom,true);
		$criteria->compare('ipn_track_id',$this->ipn_track_id,true);
		$criteria->compare('payer_email',$this->payer_email,true);
		$criteria->compare('receiver_email',$this->receiver_email,true);
		$criteria->compare('mc_currency',$this->mc_currency,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Donation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
