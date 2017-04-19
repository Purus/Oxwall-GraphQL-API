<?php

/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is a proprietary licensed product. 
 * For more information see License.txt in the plugin folder.

 * ---
 * Copyright (c) 2012, Purusothaman Ramanujam
 * All rights reserved.

 * Redistribution and use in source and binary forms, with or without modification, are not permitted provided.

 * This plugin should be bought from the developer by paying money to PayPal account (purushoth.r@gmail.com).

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
class GRAPHQL_BOL_GeneralService {

    private static $classInstance;

    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct() {
        
    }

    public function getSiteInfo() {
        return [
            'url' => OW_URL_HOME,
            'name' => OW::getConfig()->getValue('base', 'site_name'),
            'description' => OW::getConfig()->getValue('base', 'site_description'),
            'tagline' => OW::getConfig()->getValue('base', 'site_tagline'),
            'email' => OW::getConfig()->getValue('base', 'site_email'),
            'maintenance' => (int) OW::getConfig()->getValue('base', 'maintenance') == 1,
            'currency' => OW::getConfig()->getValue('base', 'billing_currency'),
            'version' => OW::getConfig()->getValue('base', 'soft_version'),
            'activePlugins' => $this->getActivePlugins(),
            'primaryMenu' => $this->getPrimayMenu(),
            'secondaryMenu' => $this->getSecondrayMenu()
        ];
    }

    public function getActivePlugins() {
        $plugins = BOL_PluginService::getInstance()->findActivePlugins();

        $activePlugins = array();
        $i = 0;

        foreach ($plugins as $plugin) {
            if (!$plugin->isSystem()) {
                $activePlugins[$i]['key'] = $plugin->getKey();
                $activePlugins[$i]['name'] = $plugin->getTitle();
                $i++;
            }
        }

        return $activePlugins;
    }

    public function getPrimayMenu() {
        $menuItems = OW::getDocument()->getMasterPage()->getMenu(BOL_NavigationService::MENU_TYPE_MAIN)->getMenuItems();

        $allMenus = array();
        $i = 0;

        foreach ($menuItems as $menu) {
            $allMenus[$i]['key'] = $menu->getKey();
            $allMenus[$i]['prefix'] = $menu->getPrefix();
            $i++;
        }

        return $allMenus;
    }

    public function getSecondrayMenu() {
        $menuItems = BOL_NavigationService::getInstance()->findMenuItems(BOL_NavigationService::MENU_TYPE_BOTTOM);

        $allMenus = array();
        $i = 0;

        foreach ($menuItems as $menu) {
            $allMenus[$i]['key'] = $menu['key'];
            $allMenus[$i]['prefix'] = $menu['prefix'];
            $i++;
        }

        return $allMenus;
    }

}
