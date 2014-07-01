<?php
// Facebook Box Extension for Bolt
namespace FacebookBox;

use Bolt\Extensions\Snippets\Location as SnippetLocation;

class Extension extends \Bolt\BaseExtension {
	public function info() {
		$data = array (
				'name' => "Facebook Box",
				'description' => "A small extension to add a 'Facebook Box' to your site, " . "when using <code>{{ facebookBox() }}</code> in your templates, mostly in sidebar or footer.",
				'author' => "kanel soeng",
				'link' => "http://bolt.cm",
				'version' => "1.0",
				'required_bolt_version' => "1.0",
				'highest_bolt_version' => "1.0",
				'type' => "Twig function",
				'first_releasedate' => "2014-7-01",
				'latest_releasedate' => "2014-7-01",
				'allow_in_user_content' => true 
		);
		
		return $data;
	}
	public function initialize() {
		if (empty ( $this->config ['appid'] )) {
			$this->config ['appid'] = "https://developers.facebook.com/apps";
		}
		if (empty ( $this->config ['url'] )) {
			$this->config ['url'] = "https://www.facebook.com/ktomouk";
		}
		if (empty ( $this->config ['scheme'] )) {
			$this->config ['scheme'] = "light";
		}
		if (empty ( $this->config ['language'] )) {
			$this->config ['language'] = "en_US";
		}
		
		$this->insertSnippet ( SnippetLocation::END_OF_BODY, 'facebookScript' );
		$this->addTwigFunction ( 'facebookbox', 'facebookBox' );
	}
	public function facebookScript() {
		$language = $this->config ['language'];
		$app_id = $this->config ['app_id'];
		$html = <<< EOM
        <div id="fb-root"></div>
			<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/$language/sdk.js#xfbml=1&appId=$app_id&version=v2.0";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			</script>
EOM;
		
		return $html;
	}
	public function facebookBox() {
		
		// code https://developers.facebook.com/docs/plugins/like-box-for-pages
		$html = <<< EOM
   				<div class="fb-like-box" data-href="%href%" 
					data-width="%width%" data-height="230" data-colorscheme="%scheme%" 
					data-show-faces="%show_faces%" data-header="%header%" data-stream="%stream%" 
					data-show-border="%show_borderr%">
				</div>
EOM;
		
		$html = str_replace ( "%href%", $this->config ['href'], $html );
		$html = str_replace ( "%height%", $this->config ['height'], $html );
		$html = str_replace ( "%width%", $this->config ['width'], $html );
		
		$html = str_replace ( "%show_faces%", $this->config ['show_faces'], $html );
		$html = str_replace ( "%show_border%", $this->config ['show_border'], $html );
		$html = str_replace ( "%header%", $this->config ['header'], $html );
		
		$html = str_replace ( "%stream%", $this->config ['stream'], $html );
		$html = str_replace ( "%scheme%", $this->config ['scheme'], $html );
		
		return new \Twig_Markup ( $html, 'UTF-8' );
	}

}
