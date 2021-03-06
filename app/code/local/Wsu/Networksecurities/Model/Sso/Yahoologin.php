<?php
class Wsu_Networksecurities_Model_Sso_Yahoologin extends Wsu_Networksecurities_Model_Sso_Abstract {
	var $_providerName = 'yahoo';
	public function getConsumerKey() {
		return trim(Mage::getStoreConfig('wsu_networksecurities/yahoo_login/consumer_key'));
	}
	
	public function getConsumerSecret() {
		return trim(Mage::getStoreConfig('wsu_networksecurities/yahoo_login/consumer_secret'));
	}
	
	public function getAppId() {
		return trim(Mage::getStoreConfig('wsu_networksecurities/yahoo_login/app_id'));
	}

	
	public function createProvider() {
		try{
			if(!class_exists("OAuthConsumer")) {
				require_once(Mage::getBaseDir('lib').DS.'Yahoo'.DS.'OAuth.php');
			}
			require_once(Mage::getBaseDir('lib').DS.'Yahoo'.DS.'Yahoo.inc');
		}catch(Exception $e) {}
		
		$google = new Google_Client;
		$google->setClientId($this->getConsumerKey())
				->setClientSecret($this->getConsumerSecret())
				->setRedirectUri($this->getRedirectUri());
		return $google;
	}
	
	
	
	public function __construct() {
		try{
			if(!@class_exists("OAuthConsumer")) {
				require_once(Mage::getBaseDir('lib').DS.'Yahoo'.DS.'OAuth.php');
			}
			//var_dump(Mage::getBaseDir('lib'));die();
			require_once(Mage::getBaseDir('lib').DS.'Yahoo'.DS.'Yahoo.inc');
		}catch(Exception $e) {}
		//error_reporting(E_ALL | E_NOTICE); # do not show notices as library is php4 compatable
		//ini_set('display_errors', true);
		$yLog = new YahooLogger();
		$yLog->setDebug(true);
		$yLog->setDebugDestination('LOG');
		
		// use memcache to store oauth credentials via php native sessions
		//ini_set('session.save_handler', 'files');
		//session_save_path('/tmp/');
		//session_start();
		$request=Mage::app()->getRequest();
		$logout=$request->getParam('logout');
		if(isset($logout)) {
			@YahooSession::clearSession();
		}
	}
	
	public function hasSession() {
		$consumerKey = $this->getConsumerKey();
		$consumerSecret = $this->getConsumerSecret();
		$appId = $this->getAppId();
		return @YahooSession::hasSession($consumerKey, $consumerSecret, $appId);
	}
	
	public function getAuthUrl() {
		$consumerKey = $this->getConsumerKey();
		$consumerSecret = $this->getConsumerSecret();
		$callback = @YahooUtil::current_url().'?in_popup';
		return @YahooSession::createAuthorizationUrl($consumerKey, $consumerSecret, $callback);
	}
	
	public function getSession() {
		$consumerKey = $this->getConsumerKey();
		$consumerSecret = $this->getConsumerSecret();
		$appId = $this->getAppId();
		return @YahooSession::requireSession($consumerKey, $consumerSecret, $appId);
	}
	


}