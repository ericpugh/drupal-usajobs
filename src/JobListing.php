<?php

/**
 * @file
 * Contains \Drupal\usajobs\JobListing.
 */

namespace Drupal\usajobs;

/**
 * Defines a Job Listing.
 */
class JobListing {

  public $positionID;
  public $positionTitle;
  public $applyURI;
  public $positionLocation;
  public $organizationName;
  public $departmentName;
  public $jobCategory;
  public $jobGrade;
  public $positionSchedule;
  public $positionOfferingType;
  public $qualificationSummary;
  public $positionRemuneration;
  public $positionFormattedDescription;
  public $jobSummary;
  public $whoMayApply;
  public $applicationCloseDate;

  //
  function __construct($data = null) {
    if($data){
      if( isset($data['MatchedObjectDescriptor']['$positionTitle']) ){
        $this->positionTitle = $data['MatchedObjectDescriptor']['$positionTitle'];
      }

    }
  }
}