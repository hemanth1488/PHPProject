<?php   

defined ('FACEBOOK_APP') or die('restricted access');
//error_reporting(E_ALL ^ E_NOTICE);

require_once './lib/helper.php';

header('Expires: 0');
header('Cache-control: private');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header("Content-Type: text/csv; charset=UTF-8");
header('Content-disposition: attachment; filename=Week'.$document->ref["weekId"].'.csv');

/*
//UTF-8 BOM signature. must present
echo chr(239); //EF
echo chr(187); //BB
echo chr(191); //BF
*/
 echo "FACEBOOK_ID,FACEBOOK_NAME,FACEBOOK_EMAIL,IMAGE_NAME,TAG_NAME,WINNER";
 echo "\n";
 foreach ($document->ref['ikea_users'] as $user){

 	 echo Helper::formatCSV($user->fb_id). ',';
 	 echo Helper::formatCSV($user->fb_name). ',';
 	 echo Helper::formatCSV($user->fb_email). ',';
 	 echo Helper::formatCSV($user->image_name). ',';
 	 echo Helper::formatCSV($user->tag_name). ',';
 	 	if($user->is_winner == 1)
 	 		echo "Yes";
 	 	else 
 	 		echo "No";
 	 	
 	 echo "\n";
 	 
 	}
 	/*
 	echo $id . ',';
 	echo $fb_id . ',';
 	echo $fb_email . ',';
 	echo $photo_id . ',';
 	echo $tag_id . ',';
 	echo "\n";
 	*/
 

/*
	echo "USER_ID,USER_FIRST_NAME,USER_LAST_NAME, USER_DOB,USER_GENDER,USER_NATIONALITY,USER_RESIDENCE,USER_EMAIL, USER_CONTACT_NUM,USER_COMPANY,USER_CAREER, USER_JOB,USER_ADDRESS,USER_YEAR_EXP,USER_INSEAD_INTERESTED,USER_INSEAD_ALUMNUS, USER_YEARS_MANAGERIAL, USER_INSEAD_GRADUATE,USER_CREATETIME,USER_SUBMITGE, USER_DOWNLOAD_BROCHURE,USER_SUBMITCV, USER_DOWNLOAD_FORM,USER_SUBSCRIBE";
	echo "\n";
	foreach($document->ref['users'] as $user)
	{
		echo Helper::formatCSV($user->USER_ID) . ',';
		echo Helper::formatCSV($user->USER_FIRST_NAME) . ',';
		echo Helper::formatCSV($user->USER_LAST_NAME) . ',';
		echo Helper::formatCSV($user->USER_DOB) . ',';
		echo Helper::formatCSV($user->USER_GENDER) . ',';
		echo Helper::formatCSV($user->USER_NATIONALITY) . ',';
		echo Helper::formatCSV($user->USER_RESIDENCE) . ',';
		echo Helper::formatCSV($user->USER_EMAIL) . ',';
		echo Helper::formatCSV($user->USER_CONTACT_NUM) . ',';
		echo Helper::formatCSV($user->USER_COMPANY) . ',';
		echo Helper::formatCSV($user->USER_CAREER) . ',';
		echo Helper::formatCSV($user->USER_JOB) . ',';
		echo Helper::formatCSV($user->USER_ADDRESS) . ',';
		echo Helper::formatCSV($user->USER_YEAR_EXP) . ',';
		echo Helper::formatCSV($user->USER_INSEAD_INTERESTED) . ',';
		echo Helper::formatCSV($user->USER_INSEAD_ALUMNUS) . ',';
		echo Helper::formatCSV($user->USER_YEARS_MANAGERIAL) . ',';
		echo Helper::formatCSV($user->USER_INSEAD_GRADUATE) . ',';
		echo Helper::formatCSV($user->USER_CREATETIME) . ',';
		echo Helper::formatCSV($user->USER_SUBMITGE) . ',';
		echo Helper::formatCSV($user->USER_DOWNLOAD_BROCHURE) . ',';
		echo Helper::formatCSV($user->USER_SUBMITCV) . ',';
		echo Helper::formatCSV($user->USER_DOWNLOAD_FORM) . ',';
		echo Helper::formatCSV($user->USER_SUBSCRIBE);
		echo "\n";
	}

*/
?>