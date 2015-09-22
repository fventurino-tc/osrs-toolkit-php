<?php

use OpenSRS\domains\lookup\GetNotes;
/**
 * @group lookup
 * @group GetNotes
 */
class GetNotesTest extends PHPUnit_Framework_TestCase
{
    protected $func = "lookupGetNotes";

    protected $validSubmission = array(
        'attributes' => array(
            'domain' => '',
            'type' => '',
            ),
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
        $data = json_decode( json_encode ($this->validSubmission) );

        $data->attributes->domain = 'phptest' . time() . '.com';
        $data->attributes->type = 'test-type';

        $ns = new GetNotes( 'array', $data );

        $this->assertTrue( $ns instanceof GetNotes );
    }

    /**
     * Data Provider for Invalid Submission test
     */
    function submissionFields() {
        return array(
            'missing domain' => array('domain'),
            'missing type' => array('type'),
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
    public function testInvalidSubmissionFieldsMissing( $field, $parent = 'attributes', $message = null ) {
        $data = json_decode( json_encode($this->validSubmission) );

        $data->attributes->domain = 'phptest' . time() . '.com';
        $data->attributes->type = 'test-type';

        $this->setExpectedException( 'OpenSRS\Exception' );

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

        $ns = new GetNotes( 'array', $data );
     }
}