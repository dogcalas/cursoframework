<?php

$id = $this->data['id'];
$SPs = $this->data['SPs'];
$timeout = $this->data['timeout'];

$iframeURL = 'logout-iframe.php?type=embed&id=' . urlencode($id) . '&timeout=' . (string)$timeout;

/* Pretty arbitrary height, but should have enough safety margins for most cases. */
$iframeHeight = 25 + count($SPs) * 4;

$this->data['header'] = $this->t('{logout:progress}');
$this->includeAtTemplateBase('includes/header.php');
echo '<iframe style="width:100%; height:' . $iframeHeight . 'em; border:0;" src="' . htmlspecialchars($iframeURL) . '"></iframe>';

foreach ($SPs AS $assocId => $sp) {
	$spId = sha1($assocId);

	if ($sp['core:Logout-IFrame:State'] !== 'inprogress') {
		continue;
	}
	assert('isset($sp["core:Logout-IFrame:URL"])');

	$url = $sp["core:Logout-IFrame:URL"];

	echo('<iframe style="width:0; height:0; border:0;" src="' . htmlspecialchars($url) . '"></iframe>');
}

$this->includeAtTemplateBase('includes/footer.php');
