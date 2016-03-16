<?php

/**
 * @file
 * Contains \Drupal\usajobs\JobListing.
 */

namespace Drupal\usajobs;

use Drupal\Core\Datetime\DateFormatter;
use Drupal\Component\Utility\Unicode;

/**
 * Defines a Job Listing.
 */
class JobListing {

  public $positionID;
  public $positionTitle;
  public $positionURI;
  public $positionLocations;
  public $organizationName;
  public $departmentName;
  public $jobCategories;
  public $jobGrade;
  public $jobLowGrade;
  public $jobHighGrade;
  public $positionSchedule;
  public $positionOfferingType;
  public $qualificationSummary;
  public $positionSalaryRange;
  public $positionFormattedDescription;
  public $jobSummary;
  public $whoMayApply;
  public $whoMayApplySummary;
  public $publicationStartDate;
  public $applicationCloseDate;

  /**
   *
   * @param object $data
   *   Search result object returned from the USAjobs Search API
   */
  function __construct($data = null) {

    if($data){
      if( $data->MatchedObjectDescriptor->PositionID ){
        $this->positionID = $data->MatchedObjectDescriptor->PositionID;
      }
      if( $data->MatchedObjectDescriptor->PositionTitle ){
        $this->positionTitle = $data->MatchedObjectDescriptor->PositionTitle;
      }
      if( $data->MatchedObjectDescriptor->PositionURI ){
        $this->positionURI = $data->MatchedObjectDescriptor->PositionURI;
      }
      if( $data->MatchedObjectDescriptor->PositionLocation ){
        $this->positionLocations = $data->MatchedObjectDescriptor->PositionLocation;
      }
      if( $data->MatchedObjectDescriptor->OrganizationName ){
        $this->organizationName = $data->MatchedObjectDescriptor->OrganizationName;
      }
      if( $data->MatchedObjectDescriptor->DepartmentName ){
        $this->departmentName = $data->MatchedObjectDescriptor->DepartmentName;
      }
      if( $data->MatchedObjectDescriptor->JobCategory ){
        $this->jobCategories = $data->MatchedObjectDescriptor->JobCategory;
      }
      if( $data->MatchedObjectDescriptor->JobGrade ){
        $this->jobGrade = $data->MatchedObjectDescriptor->JobGrade[0]->Code;
      }
      if( $data->MatchedObjectDescriptor->UserArea->Details->LowGrade ){
        $this->jobLowGrade = $data->MatchedObjectDescriptor->UserArea->Details->LowGrade;
      }
      if( $data->MatchedObjectDescriptor->UserArea->Details->HighGrade ){
        $this->jobHighGrade = $data->MatchedObjectDescriptor->UserArea->Details->HighGrade;
      }
      if( $data->MatchedObjectDescriptor->PositionSchedule ){
        $this->positionSchedule = $data->MatchedObjectDescriptor->PositionSchedule[0]->Name;
      }
      if( $data->MatchedObjectDescriptor->PositionOfferingType ){
        $this->positionOfferingType = $data->MatchedObjectDescriptor->PositionOfferingType[0]->Name;
      }
      if( $data->MatchedObjectDescriptor->UserArea->Details->JobSummary ){
        $this->jobSummary = $data->MatchedObjectDescriptor->UserArea->Details->JobSummary;
      }
      if( $data->MatchedObjectDescriptor->QualificationSummary ){
        $this->qualificationSummary = $data->MatchedObjectDescriptor->QualificationSummary;
      }
      if( $data->MatchedObjectDescriptor->PositionRemuneration ){
        $this->positionSalaryRange = $data->MatchedObjectDescriptor->PositionRemuneration[0];
      }
      if( $data->MatchedObjectDescriptor->UserArea->Details->WhoMayApply ){
        $this->whoMayApply = $data->MatchedObjectDescriptor->UserArea->Details->WhoMayApply->Name;
        if($this->whoMayApply){
          //truncate the WhoMayApply string as summary
          $this->whoMayApplySummary = Unicode::truncate($this->whoMayApply, $max_length = 57, TRUE, TRUE);
        }
      }

      $date_formatter = \Drupal::service('date.formatter');

      if( $data->MatchedObjectDescriptor->PublicationStartDate ){
        $timestamp = strtotime($data->MatchedObjectDescriptor->PublicationStartDate);
        $this->publicationStartDate = $date_formatter->format($timestamp);
      }
      if( $data->MatchedObjectDescriptor->ApplicationCloseDate ){
        $timestamp = strtotime($data->MatchedObjectDescriptor->PublicationStartDate);
        $this->applicationCloseDate = $date_formatter->format($timestamp);
      }

    }
  }

  /**
   * {@inheritdoc}
   */
  function __toString() {
    return (string) $this->positionTitle;
  }

  /**
   * Check if JobListing properties are set
   */
  function hasProperties(){
    return (
      array_filter( get_object_vars($this),
        function($val){
          // not an empty string or null value
          return ( is_string($val) && strlen($val)) || ($val!==null);
        }
      )
    ) ? true : false;
  }

}