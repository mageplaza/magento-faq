<?php
class Mageplaza_Kb_Helper_Config extends Mage_Core_Helper_Abstract
{

    /**
     * get general config by code
     *
     * @param      $code
     * @param null $store
     * @return mixed
     */
    public function getGeneralConfig($code, $store = null)
    {
        return Mage::getStoreConfig('mageplaza_kb/general/' . $code, $store);
    }

    public function getHomeConfig($code, $store = null)
    {
        return Mage::getStoreConfig('mageplaza_kb/home/' . $code, $store);
    }














}