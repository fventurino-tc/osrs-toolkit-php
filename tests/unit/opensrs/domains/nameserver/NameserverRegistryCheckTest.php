<?php

use OpenSRS\domains\nameserver\NameserverRegistryCheck;
/**
 * @group nameserver
 * @group NameserverRegistryCheck
 */
class NameserverRegistryCheckTest extends PHPUnit_Framework_TestCase
{
    protected $func = 'nsRegistryAdd';

    protected $validSubmission = array(
        "data" => array(
            /**
             * Required
             *
             * fqdn: nameserver to be checked, must
             *   be fully qualified domain name
             * tld: TLD of the nameserver you want
             *   to check
             *   * if not supplied, the tld is
             *     extracted from the 'fqdn' field
             */
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

        $data->data->fqdn = "ns1." . "phptest" . time() . ".com";
        $data->data->tld = ".com";

        $ns = new NameserverRegistryCheck( 'array', $data );

        $this->assertTrue( $ns instanceof NameserverRegistryCheck );
    }

    /**
     * Data Provider for Invalid Submission test
     */
    function submissionFields() {
        return array(
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

        $ns = new NameserverRegistryCheck( 'array', $data );
    }
}
