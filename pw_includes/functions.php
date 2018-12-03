<?php 

// Reusable Variables Variables
$homepage = $pages->get('/');
$settings = $pages->get('/settings/');

// Get Page Title: returns title for page
function getPageTitle($pages, $page) {
	$output = "";
    if ($page !== $pages->get("/")) $output .= $page->title . ' - ';
    $output .= $pages->get("/settings/")->site_title;
	if ($page == $pages->get("/")) $output .= ": " . $pages->get("/settings/")->site_tagline;
	return $output;
}

// Get Page SEO Description: returns SEO description for page
function getPageSeoDescription($pages, $page) {
	$output = "";
	if (count($page->_description)) $output .= $page->seo_description;
	elseif ($page->template !== 'home' and count($page->parent->seo_description)) 
		$output .= $page->parent->seo_description;
	else $output .= $pages->get("/settings/")->seo_description;
	return $output;
}

// Display Navigation: prints leveled navigation
function displayNavigation($pages, $page, $includeHome) {

	// Print link to homepage if necessary
	if ($includeHome) {
		echo "<a href='{$pages->get("/")->url}'><div class='nav";
    	if ($page == $pages->get("/")) echo " active";
    	echo "'>{$pages->get('/')->title}</div></a>";
	}

	// Print visible children
	foreach ($pages->get("/")->children as $child) {

		// Determine if page has children
		$createsub = $child->numChildren > 0;

		// Open nav div
		echo "<div class='nav";
		if ($child == $page or ($child == $page->parent and $page->parent !== $pages->get('/'))) echo " active";
		echo "'>";

		// Create child link
		echo "<a href='{$child->url}'>{$child->title}</a>";

		// Create subnavigation
		if ($createsub) {
			echo "<div class='subnav'>";

			// Create child link in subnav for mobile
			echo "<a href='{$child->url}' class='mobileChildLink'>{$child->title}</a>";

			// Create links for children of child
			foreach ($child->children as $sub) {
				echo "<a href='{$sub->url}' class='sublink'>{$sub->title}</a>";
			}
			echo "</div>";
		}
		
		// Close nav div
		echo "</div>";
	}
}

// Display Social Networking: prints social networking links
function displaySocialNetworks ($settings) {
	foreach ($settings->site_social as $item) {
		echo "<a href='{$item->link}'><i class='fab fa-{$item->icon}'></i></a>";
	}
}

// Display Contact Information: prints social networking links
function displayContactInformation ($settings) {
	if ($settings->contact->email) {
		echo "<div class='title'>Email</div>";
		echo "<div class='value'><a href='mailto:{$settings->contact->email}'>{$settings->contact->email}</a></div>";
	}
	if ($settings->contact->phone) {
		echo "<div class='title'>Phone</div>";
		echo "<div class='value'>{$settings->contact->phone}</div>";
	}
	if ($settings->contact->address) {
		echo "<div class='title'>Address</div>";
		echo "<div class='value'>{$settings->contact->address1}<br />{$settings->contact->address2}</div>";
	}
	if ($settings->contact->fax) {
		echo "<div class='title'>Fax</div>";
		echo "<div class='value'>{$settings->contact->fax}</div>";
	}
}

// Display Admin Links: prints admin links if user is logged in
function displayAdminLinks ($pages, $page, $config) {
	if ($page->editable()) {
		echo "<div id='processwire'>";
			echo "<a href='{$config->urls->admin}'>Admin</a><br />";
			echo "<a href='{$config->urls->admin}page/edit/?id={$pages->get('/settings/')->id}'>Settings</a><br />";
			echo "<a href='{$config->urls->admin}page/edit/?id={$page->id}'>Edit Page</a>";
		echo "</div>";
    }
}

?>