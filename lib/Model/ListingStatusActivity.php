<?php

namespace xepan\listing;

class Model_ListingStatusActivity extends \xepan\base\Model_Table{

	public $table='listing_status_activity';
	public $status = ['Active','Inactive'];
	public $actions = [
					'Active'=>['view','deactivate','edit','delete'],
					'Inactive'=>['view','activate','edit','delete']
				];
	public $acl_type = "Listing\ListingStatusActivity";

	function init(){
		parent::init();

		$this->hasOne('xepan\listing\Model_List','list_id');
		$this->addField('on_status')->display(array('form'=>'xepan\base\NoValidateDropDown'));
		$this->addField('email_subject');
		$this->addField('email_body')->type('text')->display(array('form'=>'xepan\base\RichText'));
		$this->addField('sms_content')->type('text');

		$this->addField('email_send_to_creator')->type('boolean');
		$this->addField('email_send_to_list_data_fields')->display(array('form'=>'xepan\base\NoValidateDropDown'));
		$this->addField('email_send_to_custom_email_ids')->type('text');

		$this->addField('sms_send_to_creator')->type('boolean');
		$this->addField('sms_send_to_list_data_fields')->display(array('form'=>'xepan\base\NoValidateDropDown'));
		$this->addField('sms_send_to_custom_phone_numbers')->type('text');

		$company_m = $this->add('xepan\base\Model_ConfigJsonModel',
				[
					'fields'=>[
								'company_name'=>"Line",
								'company_owner'=>"Line",
								'mobile_no'=>"Line",
								'company_email'=>"Line",
								'company_address'=>"Line",
								'company_pin_code'=>"Line",
								'company_description'=>"xepan\base\RichText",
								'company_logo_absolute_url'=>"Line",
								'company_twitter_url'=>"Line",
								'company_facebook_url'=>"Line",
								'company_google_url'=>"Line",
								'company_linkedin_url'=>"Line",
								],
					'config_key'=>'COMPANY_AND_OWNER_INFORMATION',
					'application'=>'communication'
				]);
			$company_m->tryLoadAny();

		$this->getElement('email_send_to_custom_email_ids')->defaultValue($company_m['company_email']);
		$this->getElement('sms_send_to_custom_phone_numbers')->defaultValue($company_m['mobile_no']);

		$this->addField('status')->enum($this->status)->defaultValue('Active');

		$this->add('dynamic_model\Controller_AutoCreator');
	}

	function deactivate(){
		$this['status'] = 'Inactive';
		$this->save();
	}

	function activate(){
		$this['status'] = 'Active';
		$this->save();
	}
}