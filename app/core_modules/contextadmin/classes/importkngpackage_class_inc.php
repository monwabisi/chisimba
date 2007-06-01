<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} 
// end security check
/**
 * The class importKNGPackage that manages 
 * the import of KNG content into Chisimba
 * @package importkngpackage
 * @category context
 * @copyright 2007, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @version
 * @author Jarrett Jordaan
 * The process for import KNG content is:
 * 
 * 
 */

class importKNGPackage extends object 
{
	/**
	 * @var object $objIEUtils
	*/
	public $objIEUtils;

	/**
	 * File Location handler
	 *
	 * @var object
	 */
	public $objConf;

	/**
	 * File Upload handler
	 *
	 * @var object
	 */
	public $objUpload;

	/**
	 * The constructor
	*/
	function init()
	{
		//Load Import Export Utilities class
		$this->objIEUtils = & $this->newObject('importexportutils','contextadmin');
		//Load Filemanager class
		$this->objIndex =& $this->getObject('indexfileprocessor', 'filemanager');
		$this->objUpload =& $this->getObject('upload', 'filemanager');
		$this->objConf = &$this->getObject('altconfig','config');
        	$this->objUser =& $this->getObject('user', 'security');

	}

	/**
	 * Controls the process for import KNG content
	 * Calls all necessary functions an does error checking
	 *  
	 * @param $contextcode selected course
	 * @return TRUE - Successful execution
	*/
	function importKNGcontent($contextcode)
	{
		if(!isset($contextcode))
		{
			return  "choiceError";
		}
		//Retrieve data within context
		$courseData = $this->objIEUtils->getKNGCourse($contextcode);
		if(!isset($courseData))
		{
			return  "courseReadError";
		}
		//Write course data to Chisimba
		$writeCourse = $this->writeKNGCourseToChisimba($courseData);
		if(!isset($writeCourse))
		{
			return  "courseWriteError";
		}
		//Write Images to Chisimba usrfiles directory
		$writeKNGImages = $this->objIEUtils->writeKNGImages($contextcode);
		if(!isset($writeKNGImages))
		{
			return  "courseWriteError";
		}
		//Upload KNG Images to Chisimba database
		$contextcodeInChisimba = strtolower(str_replace(' ','_',$contextcode));
		$contextcodeInChisimba = strtolower(str_replace('$','_',$contextcode));
		$basePath = $this->objConf->getcontentBasePath();
		$basePathNew = $basePath."content/".$contextcodeInChisimba;
		$basePathToImages = $basePathNew."/images";
		//echo $basePathToImages;
		$uploadImages = $this->uploadKNGImages($basePathToImages);
		//Write Htmls to Chisimba
		$writeKNGHtmls = $this->writeKNGHtmls($contextcode);
		if(!isset($writeKNGHtmls))
		{
			return  "courseWriteError";
		}

		return TRUE;
	}

	/**
	 * Writes course data to the new database
	 * Makes query to tbl_context in new database
	 * 
	 * @param $courseData - 2d array containing course data
	 * @return TRUE - Successful execution
	*/
	function writeKNGCourseToChisimba($courseData)
	{
		//Convert 2-d array into 1-d array
		$newCourse['contextcode'] = $courseData['0']['contextcode'];
		$newCourse['title'] = $courseData['0']['title'];
		$newCourse['menutext'] = $courseData['0']['menutext'];
		$newCourse['userid'] = $courseData['0']['userid'];
		$newCourse['about'] = $courseData['0']['about'];
		if($courseData['isactive'] == 0)
			$newCourse['isactive'] = "Public";
		else if($courseData['isactive'] == 1)
			$newCourse['isactive'] = "Open";
		else
			$newCourse['isactive'] = "Private";
		if($courseData['isclosed'] == 1)
			$newCourse['isclosed'] = "Published";
		else
			$newCourse['isclosed'] = "UnPublished";

		//Create course
		$courseCreated= $this->objIEUtils->createCourseInChisimba($newCourse);
		if(!isset($courseCreated) && $courseCreated)
		{
			return  "courseCreateError";
		}

		return TRUE;
	}

	/**
	 * Writes all images used by old course to new database
	 * Makes query to tbl_context_file
	 * 
	 * @param $contextcode selected course
	 * @return TRUE - Successful execution
	*/
	function uploadKNGImages($folder)
	{
		//Course Images


		return TRUE;
	}

	/**
	 * Writes all htmls specific to context to usrfiles directory of new system (Chisimba)
	 * For modification before insertion into new database
	 *
	 * @param $contextcode selected course
	 * @return TRUE - Successful execution
	*/
	function writeKNGHtmls($contextcode)
	{
		//Course htmls

		
		return TRUE;
	}

	/**
	 *  Writes all pages created in old course to new database
	 *  Makes query to tbl_context_page_content
	 * 
	 * @param $contextcode selected course
	 * @return TRUE - Successful execution
	*/
	function uploadHtmls($contextcode)
	{


		
		return TRUE;
	}

}