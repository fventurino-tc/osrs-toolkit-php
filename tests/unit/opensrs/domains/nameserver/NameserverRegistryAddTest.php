<?php

use OpenSRS\domains\nameserver\NameserverRegistryAdd;
/**
 * @group nameserver
 * @group NameserverRegistryAdd
 */
class NameserverRegistryAddTest extends PHPUnit_Framework_TestCase
{
    protected $func = 'nsRegistryAdd';

    protected $validSubmission = array(
        "data" => array(
            /**
             * Required
             *
             * all: specify whether to add nameserver
             *   to all registries or not
             *   0 = add only to registry specified
             *       in 'tld' parameter
             *   1 = add to all registries enabled
             *       on your account
             * fqdn: nameserver to be added, must
             *   be fully qualified domain name
             * tld: registry to which you want to add
             *   the nameserver, value can be any tld
             *   available through OpenSRS ie: .com
             *   * if 'all' is 1, 'tld' is ignored
             *     however it must be present and
             *     the value must be a valid tld,
             *     otherwise the command will fail
             */
            "all" => "",
            "fqdn" => "",
            "tld" => "",
            )
        );

    /**
     * Valid submission should complete with no
     * exception thrown
     *
     * @return void
     *
     * @group validsubmission
     */
    public function testValidSubmission() {
        $data = json_decode( json_encode($this->validSubmission) );

        $data->data->all = "0";
        $data->data->fqdn = "ns1." . "phptest" . time() . ".com";
        $data->data->tld = ".com";

        $ns = new NameserverRegistryAdd( 'array', $data );

        $this->assertTrue( $ns instanceof NameserverRegistryAdd );
    }

    /**
     * Data Provider for Invalid Submission test
     */
    function submissionFields() {
        return array(
            'missing all' => array('all'),
            'missing fqdn' => array('fqdn'),
            'missing tld' => array('tld'),
            );
    }

    /**
     * Invalid submission should throw an exception
     *
     * @return void
     *
     * @dataProvider submissionFields
     * @group invalidsubmission
     */
    public function testInvalidSubmissionFieldsMissing( $field, $parent = 'data', $message = null ) {
        $data = json_decode( json_encode($this->validSubmission) );

        $data->data->all = "0";
        $data->data->fqdn = "ns1." . "phptest" . time() . ".com";
        $data->data->tld = ".com";

        if(is_null($message)){
          $this->setExpectedExceptionRegExp(
              'OpenSRS\Exception',
              "/$field.*not defined/"
              );
        }
        else {
          $this->setExpectedExceptionRegExp(
              'OpenSRS\Exception',
              "/$message/"
              );
        }



        // clear field being tested
        if(is_null($parent)){
            unset( $data->$field );
        }
        else{
            unset( $data->$parent->$field );
        }

        $ns = new NameserverRegistryAdd( 'array', $data );
    }
}
