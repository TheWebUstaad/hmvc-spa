<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo html_escape(isset($page_title) ? $page_title : 'Sobeat'); ?></title>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/spa.css'); ?>">
</head>
<body data-base-url="<?php echo html_escape(rtrim(base_url(), '/').'/'); ?>"
      data-site-url="<?php echo html_escape(rtrim(site_url(), '/').'/'); ?>"
      data-spa-container="<?php echo html_escape($spa_container); ?>">

	<header class="spa-header">
		<a class="spa-brand spa-link" href="<?php echo site_url('home'); ?>">Sobeat</a>
		<nav class="spa-nav" aria-label="Main">
			<?php if ( ! empty($spa_nav)): foreach ($spa_nav as $slug => $label): ?>
				<a class="spa-link" href="<?php echo site_url($slug); ?>" data-module="<?php echo html_escape($slug); ?>">
					<?php echo html_escape($label); ?>
				</a>
			<?php endforeach; endif; ?>
		</nav>
	</header>

	<main id="<?php echo html_escape($spa_container); ?>" class="spa-main" aria-live="polite">
		<?php echo $spa_content; ?>
	</main>

	<footer class="spa-footer">
		<small>HMVC + SPA &middot; CodeIgniter <?php echo CI_VERSION; ?></small>
	</footer>

	<script src="<?php echo base_url('assets/js/spa.js'); ?>" defer></script>
</body>
</html>
