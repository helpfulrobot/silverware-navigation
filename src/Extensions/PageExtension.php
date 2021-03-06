<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Navigation\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-navigation
 */

namespace SilverWare\Navigation\Extensions;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Permission;

/**
 * A data extension which adds navigation settings to pages.
 *
 * @package SilverWare\Navigation\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-navigation
 */
class PageExtension extends DataExtension
{
    /**
     * Maps field names to field types for the extended object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'CrumbsDisabled' => 'Boolean'
    ];
    
    /**
     * Defines the default values for the fields of the extended object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'CrumbsDisabled' => 0
    ];
    
    /**
     * Updates the CMS settings fields of the extended object.
     *
     * @param FieldList $fields Collection of CMS settings fields from the extended object.
     *
     * @return void
     */
    public function updateSettingsFields(FieldList $fields)
    {
        // Update Field Objects:
        
        $fields->addFieldToTab(
            'Root.Settings',
            $settings = CompositeField::create([
                CheckboxField::create(
                    'CrumbsDisabled',
                    $this->owner->fieldLabel('CrumbsDisabled')
                )
            ])->setName('NavigationSettings')->setTitle($this->owner->fieldLabel('NavigationSettings'))
        );
        
        // Check Permissions and Modify Fields:
        
        if (!Permission::check(['ADMIN', 'SILVERWARE_PAGE_SETTINGS_CHANGE'])) {
            
            foreach ($settings->getChildren() as $field) {
                $settings->makeFieldReadonly($field);
            }
            
        }
    }
    
    /**
     * Updates the field labels of the extended object.
     *
     * @param array $labels Array of field labels from the extended object.
     *
     * @return void
     */
    public function updateFieldLabels(&$labels)
    {
        $labels['CrumbsDisabled'] = _t(__CLASS__ . '.CRUMBSDISABLED', 'Crumbs disabled');
        $labels['NavigationSettings'] = _t(__CLASS__ . '.NAVIGATION', 'Navigation');
    }
}
