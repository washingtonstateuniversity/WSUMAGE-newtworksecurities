<?php
class Wsu_Networksecurities_Model_Sso_Livejournallogin extends Mage_Core_Model_Abstract {
	public function newProvider() {
		try{
			require_once Mage::getBaseDir('base').DS.'lib'.DS.'OpenId'.DS.'openid.php';
		}catch(Exception $e) {}
		$openid = new LightOpenID(Mage::getUrl());       
		return $openid;
	}
	public function getLoginUrl($identity) {
		$my_id = $this->newProvider();
        $my = $this->setIdlogin($my_id,$identity);
		$loginUrl = $my->authUrl();
		return $loginUrl;
	}
	public function setIdlogin($openid,$identity) {
        $openid->identity = "http://".$identity.".livejournal.com";
        $openid->required = array(
			'namePerson/first',
			'namePerson/last',
			'namePerson/friendly',
			'contact/email'
        );
        $openid->returnUrl = Mage::getUrl('sociallogin/livejournallogin/login');
		return $openid;
    }
}
  