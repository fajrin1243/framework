<?php

/* This function is lists type data */

//get instance access
use system\framework as framework;

class lists
{
	public static function transaksi_lists()
	{
		$lists = array(
			"self"=>"Isi Account Anda",
			"transfer"=>"Transfer Ke Rekening Contact",
			"payment"=>"Pembelian / Pembayaran",
			"withdrawal"=>"Penarikan Tunai"
			);
			
		return $lists;
	}
	
	public static function currency_lists()
	{
		//get instance access
		$instance = framework::$instance;
		
		$model = $instance->get('model');
		$currencies = $model->get('pay_currency','','array');
	
		$return = array();
		foreach($currencies as $currency)
		{
			$return[$currency->id] = $currency->currency;
		}
		
		return $return;

	}
	
	public static function contact_lists()
	{
		//get instance access
		$instance = framework::$instance;
		$getuser = $instance->library('getauth')->getuser();
		
		$model = $instance->get('model');
		$contacts = $model->get(array('pay_contact'=>'puser_id','pay_account'=>'user_id'),array('user_id'=>$getuser->id),'array');
		
		if(!empty($contacts))
		{
			$return = array();
			foreach($contacts as $contact)
			{
				$return[$contact->id] = $contact->account_id;
			}
			
			return $return;
		}
		else
		{
			return "";
		}

	}
	
	public static function payment_lists()
	{
		//get instance access
		$instance = framework::$instance;
		$getuser = $instance->library('getauth')->getuser();
		
		$model = $instance->get('model');
		$contacts = $model->get(array('pay_contact'=>'puser_id','pay_account'=>'user_id'),array('user_id'=>$getuser->id),'array');
		
		if(!empty($contacts))
		{
			$return = array();
			foreach($contacts as $contact)
			{
				$return[$contact->id] = $contact->account_id;
			}
			
			return $return;
		}
		else
		{
			return "";
		}

	}
	
	public static function member_payment_lists()
	{
		//get instance access
		$instance = framework::$instance;
		$getuser = $instance->library('getauth')->getuser();
		
		$model = $instance->get('model');
		$mpayments = $model->get(array('pay_member_payment'=>'payment_id','pay_payment'=>'id'),array('pay_member_payment.user_id'=>$getuser->id),'array','pay_member_payment.*,pay_paymnent.payment');
		
		if(!empty($mpayments))
		{
			$return = array();
			foreach($mpayments as $mpayment)
			{
				$return[$mpayment->id] = $mpayment->payment;
			}
			
			return $return;
		}
		else
		{
			return "";
		}

	}
	
	public static function gateway_lists()
	{
		//get instance access
		$instance = framework::$instance;
		$getuser = $instance->library('getauth')->getuser();
		
		$model = $instance->get('model');
		$gateways = $model->get(array('pay_gateway'=>'gateway_type_id','pay_gateway_type'=>'id'),array('pay_gateway.status'=>'active'),'array','pay_gateway.*,pay_gateway_type.type');
		
		if(!empty($gateways))
		{
			$return = array();
			foreach($gateways as $gateway)
			{
				$return[$gateway->id] = $gateway->gateway." : ".$gateway->account." A/N ".$gateway->nama;
			}
			
			return $return;
		}
		else
		{
			return "";
		}

	}
	
	public static function member_gateway_lists()
	{
		//get instance access
		$instance = framework::$instance;
		$getuser = $instance->library('getauth')->getuser();
		
		$model = $instance->get('model');
		$mgateways = $model->get(array('pay_member_gateway'=>'pay_gateway_id','pay_gateway'=>'id'),array('pay_member_gateway.user_id'=>$getuser->id),'array','pay_member_gateway.*,pay_gateway.gateway');
		
		if(!empty($mgateways))
		{
			$return = array();
			foreach($mgateways as $mgateway)
			{
				$return[$mgateway->id] = $mgateway->gateway." - ".$mgateway->account." A/N ".$gateway->nama;
			}
			
			return $return;
		}
		else
		{
			return "";
		}

	}
	
	public static function self_account_lists()
	{
		//get instance access
		$instance = framework::$instance;
		$getuser = $instance->library('getauth')->getuser();
		
		$model = $instance->get('model');
		$accounts = $model->get('pay_account',array('user_id'=>$getuser->id),'array');
	
		$return = array();
		foreach($accounts as $account)
		{
			$return[$account->user_id] = $account->account_id;
		}
		
		return $return;

	}
}

?>