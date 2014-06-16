<?php   

defined ('FACEBOOK_APP') or die('restricted access');

if($document->ref['users'] != null)
{ $type= "users"; }
else if($document->ref['invitations'] != null)
{ $type= "invitations"; }
else if($document->ref['profilingTools'] != null)
{ $type= "profilingTools"; }
else if($document->ref['interests'] != null)
{ $type = 'interests'; }
else if($document->ref['sessions'] != null)
{ $type = 'sessions'; }


header('Expires: 0');
header('Cache-control: private');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Content-Description: File Transfer');
header("Content-Type: text/csv; charset=UTF-8");
header('Content-disposition: attachment; filename='.$type.'_CSVreport.csv');

//UTF-8 BOM signature. must present
echo chr(239); //EF
echo chr(187); //BB
echo chr(191); //BF


if($type == 'users')
{
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
}
else if($type == 'invitations')
{
	echo "INVITATION_ID,SESSION_ID,INVITATION_COUNTRY,INVITATION_CITY,USER_ID,INVITATION_CREATETIME";
	echo "\n";
	foreach($document->ref['invitations'] as $invitation)
	{
		echo Helper::formatCSV($invitation->INVITATION_ID) . ',';
		echo Helper::formatCSV($invitation->SESSION_ID) . ',';
		echo Helper::formatCSV($invitation->INVITATION_COUNTRY) . ',';
		echo Helper::formatCSV($invitation->INVITATION_CITY) . ',';
		echo Helper::formatCSV($invitation->USER_ID) . ',';
		echo Helper::formatCSV($invitation->INVITATION_CREATETIME);
		echo "\n";
	}
}
else if($type == 'profilingTools')
{
	echo "PROFILING_ID,USER_ID,Q1_ANSWER,Q2_ANSWER,Q3_ANSWER,Q4_ANSWER,Q5_ANSWER,PROFILING_CREATETIME";
	echo "\n";
	foreach($document->ref['profilingTools'] as $profilingTool)
	{
		echo Helper::formatCSV($profilingTool->PROFILING_ID) . ',';
		echo Helper::formatCSV($profilingTool->USER_ID) . ',';
		echo Helper::formatCSV($profilingTool->Q1_ANSWER) . ',';
		echo Helper::formatCSV($profilingTool->Q2_ANSWER) . ',';
		echo Helper::formatCSV($profilingTool->Q4_ANSWER) . ',';
		echo Helper::formatCSV($profilingTool->Q5_ANSWER) . ',';
		echo Helper::formatCSV($profilingTool->Q6_ANSWER) . ',';
		echo Helper::formatCSV($profilingTool->PROFILING_CREATETIME);
		echo "\n";
	}
}
else if($type == 'interests')
{
	echo "INTEREST_ID,USER_ID,INTEREST_TYPE,MAIL_FORMAT,INTEREST_CREATETIME";
	echo "\n";
	foreach($document->ref['interests'] as $interest)
	{
		echo Helper::formatCSV($interest->INTEREST_ID) . ',';
		echo Helper::formatCSV($interest->USER_ID) . ',';
		echo Helper::formatCSV($interest->INTEREST_TYPE) . ',';
		echo Helper::formatCSV($interest->MAIL_FORMAT) . ',';
		echo Helper::formatCSV($interest->INTEREST_CREATETIME);
		echo "\n";
	}
}
else if($type == 'sessions')
{
	echo "SESSION_ID,USER_ID,SESSION_CREATETIME";
	echo "\n";
	foreach($document->ref['sessions'] as $session)
	{
		echo Helper::formatCSV($session->SESSION_ID) . ',';
		echo Helper::formatCSV($session->USER_ID) . ',';
		echo Helper::formatCSV($session->SESSION_CREATETIME);
		echo "\n";
	}
}

?>