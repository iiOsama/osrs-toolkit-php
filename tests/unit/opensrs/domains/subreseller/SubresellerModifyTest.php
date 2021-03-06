<?php

use opensrs\domains\subreseller\SubresellerModify;

/**
 * @group subreseller
 * @group SubresellerModify
 */
class SubresellerModifyTest extends PHPUnit_Framework_TestCase
{
    protected $func = 'subresModify';

    protected $validSubmission = array(
        'attributes' => array(
            /*
             * Required
             *
             * cpp_enabled: indicates if credit card
             *   payments are enabled for sub-reseller
             *   values:
             *     - N = no
             *     - Y = yes
             * low_balance_email: email address to
             *   send notice when balance falls to a
             *   predefined level
             * username: username for the sub-reseller
             * password: password for the new sub-
             *   reseller account
             * pricing_plan: pricing plan assigned to
             *   the sub-reseller
             * status: status of the account, accepted
             *   values: active, onhold, locked,
             *           cancelled, paid_only
             * system_status_email: email address that
             *   will receive system status messages
             * contact_set: associative array
             *   containing contact information for
             *   domain owner/subreseller
             *   Note: admin, billing, tech contacts
             *         are also supported. 'admin'
             *         contact is the sub-reseller's
             *         emergency contact, not domain
             *         admin contact
             */
            'ccp_enabled' => '',
            'low_balance_email' => '',
            'username' => '',
            'password' => '',
            'pricing_plan' => '',
            'status' => '',
            'system_status_email' => '',

            'contact_set' => array(
                'owner' => array(
                    'first_name' => '',
                    'last_name' => '',
                    'org_name' => '',
                    'address1' => '',
                    // address2 optional
                    'address2' => '',
                    // address3 optional
                    'address3' => '',
                    'city' => '',
                    'state' => '',
                    'country' => '',
                    'postal_code' => '',
                    'phone' => '',
                    'fax' => '',
                    'email' => '',
                    'url' => '',
                    'lang_pref' => '',
                    ),
                'admin' => array(
                    'first_name' => '',
                    'last_name' => '',
                    'org_name' => '',
                    'address1' => '',
                    // address2 optional
                    'address2' => '',
                    // address3 optional
                    'address3' => '',
                    'city' => '',
                    'state' => '',
                    'country' => '',
                    'postal_code' => '',
                    'phone' => '',
                    'fax' => '',
                    'email' => '',
                    'url' => '',
                    'lang_pref' => '',
                    ),
                'billing' => array(
                    'first_name' => '',
                    'last_name' => '',
                    'org_name' => '',
                    'address1' => '',
                    // address2 optional
                    'address2' => '',
                    // address3 optional
                    'address3' => '',
                    'city' => '',
                    'state' => '',
                    'country' => '',
                    'postal_code' => '',
                    'phone' => '',
                    'fax' => '',
                    'email' => '',
                    'url' => '',
                    'lang_pref' => '',
                    ),
                'tech' => array(
                    'first_name' => '',
                    'last_name' => '',
                    'org_name' => '',
                    'address1' => '',
                    // address2 optional
                    'address2' => '',
                    // address3 optional
                    'address3' => '',
                    'city' => '',
                    'state' => '',
                    'country' => '',
                    'postal_code' => '',
                    'phone' => '',
                    'fax' => '',
                    'email' => '',
                    'url' => '',
                    'lang_pref' => '',
                    ),
                ),

            /*
             * Optional
             *
             * url: web address of the account
             * nameservers: list of default nameservers
             *   for the sub-reseller
             * storefront_rwi: determines whether the
             *   sub-reseller sees the storefront ink
             *   in RWI (and hense whether the sub-
             *   reseller can configure a storefront).
             *   accepted values:
             *     - N = do not show link (default)
             *     - Y = show storefront link
             * payment_email: email payment notices
             *   are sent to
             * allowed_tld_list: list of TLDs sub-
             *   reseller is allowed to sell
             */
            'url' => '',
            'nameservers' => '',
            'storefront_rwi' => '',
            'payment_email' => '',
            'allowed_tld_list' => '',
            ),
        );

    /**
     * Valid submission should complete with no
     * exception thrown.
     *
     *
     * @group validsubmission
     */
    public function testValidSubmission()
    {
        $data = json_decode(json_encode($this->validSubmission));

        $data->attributes->username = 'subreseller'.time();
        $data->attributes->password = 'password1234';
        $data->attributes->ccp_enabled = 'Y';
        $data->attributes->pricing_plan = '1';
        $data->attributes->status = 'onhold';
        $data->attributes->low_balance_email = 'phptoolkit@tucows.com';
        $data->attributes->system_status_email = 'phptoolkit@tucows.com';

        $data->attributes->contact_set->owner->first_name = 'John';
        $data->attributes->contact_set->owner->last_name = 'Smith';
        $data->attributes->contact_set->owner->org_name = 'Tucows';
        $data->attributes->contact_set->owner->address1 = '96 Mowat Avenue';
        $data->attributes->contact_set->owner->address2 = '';
        $data->attributes->contact_set->owner->address3 = '';
        $data->attributes->contact_set->owner->city = 'Toronto';
        $data->attributes->contact_set->owner->state = 'ON';
        $data->attributes->contact_set->owner->country = 'CA';
        $data->attributes->contact_set->owner->postal_code = 'M6K 3M1';
        $data->attributes->contact_set->owner->phone = '+1.4165350123';
        $data->attributes->contact_set->owner->email = 'phptoolkit@tucows.com';
        $data->attributes->contact_set->owner->lang_pref = 'EN';

        $ns = new SubresellerModify('array', $data);

        $this->assertTrue($ns instanceof SubresellerModify);
    }

    /**
     * Data Provider for Invalid Submission test.
     */
    public function submissionFields()
    {
        return array(
            'missing ccp_enabled' => array('ccp_enabled'),
            'missing low_balance_email' => array('low_balance_email'),
            'missing password' => array('password'),
            'missing pricing_plan' => array('pricing_plan'),
            'missing status' => array('status'),
            'missing system_status_email' => array('system_status_email'),
            'missing username' => array('username'),
            );
    }

    /**
     * Invalid submission should throw an exception.
     *
     *
     * @dataProvider submissionFields
     * @group invalidsubmission
     */
    public function testInvalidSubmissionFieldsMissing($field, $parent = 'attributes')
    {
        $data = json_decode(json_encode($this->validSubmission));

        $data->attributes->username = 'subreseller'.time();
        $data->attributes->password = 'password1234';
        $data->attributes->ccp_enabled = 'Y';
        $data->attributes->pricing_plan = '1';
        $data->attributes->status = 'onhold';
        $data->attributes->low_balance_email = 'phptoolkit@tucows.com';
        $data->attributes->system_status_email = 'phptoolkit@tucows.com';

        $data->attributes->contact_set->owner->first_name = 'John';
        $data->attributes->contact_set->owner->last_name = 'Smith';
        $data->attributes->contact_set->owner->org_name = 'Tucows';
        $data->attributes->contact_set->owner->address1 = '96 Mowat Avenue';
        $data->attributes->contact_set->owner->address2 = '';
        $data->attributes->contact_set->owner->address3 = '';
        $data->attributes->contact_set->owner->city = 'Toronto';
        $data->attributes->contact_set->owner->state = 'ON';
        $data->attributes->contact_set->owner->country = 'CA';
        $data->attributes->contact_set->owner->postal_code = 'M6K 3M1';
        $data->attributes->contact_set->owner->phone = '+1.4165350123';
        $data->attributes->contact_set->owner->email = 'phptoolkit@tucows.com';
        $data->attributes->contact_set->owner->lang_pref = 'EN';

        $this->setExpectedExceptionRegExp(
            'opensrs\Exception',
            "/$field.*not defined/"
            );

        // clear field being tested
        if (is_null($parent)) {
            unset($data->$field);
        } else {
            unset($data->$parent->$field);
        }

        $ns = new SubresellerModify('array', $data);
    }
}
