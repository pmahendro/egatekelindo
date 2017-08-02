<?php

/**
 * @property integer $id
 * @property integer $cn_ordinal
 * @property integer $cn_month
 * @property integer $cn_year
 * @property string $date
 * @property string $note
 * @property integer $account_id
 * @property integer $admin_id
 * @property integer $is_bank
 * @property integer $is_non_tax
 * @property integer $is_inactive
 *
 * @property DepositDetail[] $depositDetails
 * @property Account $account
 * @property Admin $admin
 */
class DepositHeaderBase extends MonthlyTransactionActiveRecord
{
	public function tableName()
	{
		return 'tblet_deposit_header';
	}

	public function rules()
	{
		return array(
			array('cn_ordinal, cn_month, cn_year, date, account_id, admin_id', 'required'),
			array('cn_ordinal, cn_month, cn_year, account_id, admin_id, is_bank, is_non_tax, is_inactive', 'numerical', 'integerOnly'=>true),
			array('note', 'safe'),
			// The following rule is used by search().
			array('id, cn_ordinal, cn_month, cn_year, date, note, account_id, admin_id, is_bank, is_non_tax, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'depositDetails' => array(self::HAS_MANY, 'DepositDetail', 'deposit_header_id'),
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
			'admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cn_ordinal' => 'Cn Ordinal',
			'cn_month' => 'Cn Month',
			'cn_year' => 'Cn Year',
			'date' => 'Date',
			'note' => 'Note',
			'account_id' => 'Account',
			'admin_id' => 'Admin',
			'is_bank' => 'Is Bank',
			'is_non_tax' => 'Is Non Tax',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.cn_ordinal', $this->cn_ordinal);
		$criteria->compare('t.cn_month', $this->cn_month);
		$criteria->compare('t.cn_year', $this->cn_year);
		$criteria->compare('t.date', $this->date, true);
		$criteria->compare('t.note', $this->note, true);
		$criteria->compare('t.account_id', $this->account_id);
		$criteria->compare('t.admin_id', $this->admin_id);
		$criteria->compare('t.is_bank', $this->is_bank);
		$criteria->compare('t.is_non_tax', $this->is_non_tax);
		$criteria->compare('t.is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
