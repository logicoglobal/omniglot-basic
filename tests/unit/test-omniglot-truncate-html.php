<?php

// ./vendor/bin/phpunit --testdox ./tests/unit/test-omniglot-truncate-html.php

include_once __DIR__ . '/../../includes/class-omniglot-truncate-html.php';

use PHPUnit\Framework\TestCase;

/**
* Globally set variables.
*/
$content = '<div>
	<h3>This is 1st heading</h3>
	<p>This is the <strong>paragraph</strong></p>
	<p>This is another <strong>paragraph</strong></p>
</div>

<div>
	<h3>This is 2nd heading</h3>
	<p>This is the description block.</p>
</div>

<div>
	<h4>This is 3rd heading</h4>
	<p>My country is <strong>Bangladesh</strong></p>
	<p>I <strong>love</strong> my country</p>
</div>';

$expected = '<div>
	<h3>This is 1st heading</h3>
	<p>This is the <strong>paragraph</strong></p>
	<p>This is another <strong>paragraph</strong></p>
</div>

';

$random_content = '<div class="section" id="backupglobals">
<span id="appendixes-annotations-backupglobals"></span><h2>@backupGlobals</h2>
<p>PHPUnit can optionally backup all global and super-global variables before each test and restore this backup after each test.</p>
<p>The <code class="docutils literal"><span class="pre">@backupGlobals</span> <span class="pre">enabled</span></code> annotation can be used on the class level to enable this operation for all tests of a test case class:</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="k">use</span> <span class="nx">PHPUnit\Framework\TestCase</span><span class="p">;</span>

<span class="sd">/**</span>
<span class="sd"> * @backupGlobals enabled</span>
<span class="sd"> */</span>
<span class="k">class</span> <span class="nc">MyTest</span> <span class="k">extends</span> <span class="nx">TestCase</span>
<span class="p">{</span>
	<span class="c1">// ...</span>
<span class="p">}</span>
</pre></div>
</div>
<p>The <code class="docutils literal"><span class="pre">@backupGlobals</span></code> annotation can also be used on the
test method level. This allows for a fine-grained configuration of the
backup and restore operations:</p>
<div class="highlight-php"><div class="highlight"><pre><span></span><span class="k">use</span> <span class="nx">PHPUnit\Framework\TestCase</span><span class="p">;</span>

<span class="sd">/**</span>
<span class="sd"> * @backupGlobals enabled</span>
<span class="sd"> */</span>
<span class="k">class</span> <span class="nc">MyTest</span> <span class="k">extends</span> <span class="nx">TestCase</span>
<span class="p">{</span>
	<span class="k">public</span> <span class="k">function</span> <span class="nf">testThatInteractsWithGlobalVariables</span><span class="p">()</span>
	<span class="p">{</span>
		<span class="c1">// ...</span>
	<span class="p">}</span>

	<span class="sd">/**</span>
<span class="sd">     * @backupGlobals disabled</span>
<span class="sd">     */</span>
	<span class="k">public</span> <span class="k">function</span> <span class="nf">testThatDoesNotInteractWithGlobalVariables</span><span class="p">()</span>
	<span class="p">{</span>
		<span class="c1">// ...</span>
	<span class="p">}</span>
<span class="p">}</span>
</pre></div>
</div>
</div>';

$gutenberg_post_content = '<!-- wp:heading -->
<h2>Who we are</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our website address is: https://deepl.dev.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>What personal data we collect and why we collect it</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Comments</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor’s IP address and browser user agent string to help spam detection.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Media</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Contact forms</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Cookies</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select "Remember Me", your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Embedded content from other websites</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Articles on this <strong>site(hello this is extra content added to check)</strong> may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Analytics</h3>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>Who we share your data with</h2>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>How long we retain your data</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>What rights you have over your data</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Where we send your data</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Visitor comments may be checked through an automated spam detection service.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Your contact information</h2>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>Additional information</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>How we protect your data</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>What data breach procedures we have in place</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>What third parties we receive data from</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>What automated decision making and/or profiling we do with user data</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Industry regulatory disclosure requirements</h3>
<!-- /wp:heading -->';

class TestTruncate extends TestCase
{
	/**
	* @test
	* @testdox Should return the original content if the plain text is shorter than or equal to the maximum length, return the whole text
	*/
	public function should_return_the_original_content()
	{
		$content  = '<p>hello world</p><p>My name is shamim al mamun<br>this is another paragraph</p>';
		$expected = '<p>hello world</p><p>My name is shamim al mamun<br>this is another paragraph</p>';

		$truncate  = new Omniglot_Truncate_HTML();
		$truncate->set_length(10);
		$truncate->set_max_length(62); // 62 is the total length of the given content without the tags
		$truncate->set_content($content);
		$truncated = $truncate->get_truncated();

		$this->assertEquals($expected, $truncated);
	}

	/**
	* @test
	* @testdox Add remaining string from the parent element
	*/
	public function add_remaining_string_from_parent_element()
	{
		$content  = '<div><h3>This is the heading 3</h3><p>This is the <strong>paragraph</strong></p><p>This is another <strong>paragraph</strong></p></div><div><h3>This is another heading 3</h3>This is the description block.</div>';
		$expected = '<div><h3>This is the heading 3</h3><p>This is the <strong>paragraph</strong></p><p>This is another <strong>paragraph</strong></p></div>';

		$truncate  = new Omniglot_Truncate_HTML();
		$truncate->set_length(65);
		$truncate->set_max_length(90);
		$truncate->set_content($content);
		$truncated = $truncate->get_truncated();

		$this->assertEquals($expected, $truncated);
	}

	/**
	* @test
	* @testdox Do not add next parent element if total length becomes greater than the defined max length
	*/
	public function do_not_add_next_parent_element()
	{
		$content  = '<div><h3>This is the heading 3</h3><p>This is the <strong>paragraph</strong></p><p>This is another <strong>paragraph</strong></p></div><div><h3>This is another heading 3</h3>This is the description block.</div>';
		$expected = '<div><h3>This is the heading 3</h3><p>This is the <strong>paragraph</strong></p><p>This is another <strong>paragraph</strong></p></div>';

		$truncate  = new Omniglot_Truncate_HTML();
		$truncate->set_length(70);
		$truncate->set_max_length(90);
		$truncate->set_content($content);
		$truncated = $truncate->get_truncated();

		$this->assertEquals($expected, $truncated);
	}

	/**
	* @test
	* @testdox Adds next parent element if total length is greater than the defined length and total length after adding the next parent element is less than the defined max length.
	*/
	public function add_next_parent_element()
	{
		$content  = '<div><h3>This is the heading 3</h3><p>This is the <strong>paragraph</strong></p><p>This is another <strong>paragraph</strong></p></div><strong>hello shamim al mamun</strong><div><h3>This is another heading 3</h3>This is the description block.</div>';
		$expected = '<div><h3>This is the heading 3</h3><p>This is the <strong>paragraph</strong></p><p>This is another <strong>paragraph</strong></p></div><strong>hello shamim al mamun</strong><div><h3>This is another heading 3</h3>This is the description block.</div>';

		$truncate  = new Omniglot_Truncate_HTML();
		$truncate->set_length(90);
		$truncate->set_max_length(120);
		$truncate->set_content($content);
		$truncated = $truncate->get_truncated();

		$this->assertEquals($expected, $truncated);
	}

	/**
	* @test
	* @testdox Should return parent element only if defined max length is less than or equal to the parent element length
	*/
	public function parent_element_only()
	{
		$content  = '<p>hello world</p><p>My name is shamim al mamun<br>this is another paragraph</p>';
		$expected = '<p>hello world</p>';

		$truncate  = new Omniglot_Truncate_HTML();
		$truncate->set_length(10);
		$truncate->set_max_length(10);
		$truncate->set_content($content);
		$truncated = $truncate->get_truncated();

		$this->assertEquals($expected, $truncated);
	}

	/**
	* @test
	* @testdox Should add spaces at the end
	*/
	public function should_add_space()
	{
		$content  = '<p>hello world</p>   <p>My name is shamim al mamun<br>this is another paragraph</p>';
		$expected = '<p>hello world</p>   ';

		$truncate  = new Omniglot_Truncate_HTML();
		$truncate->set_length(10);
		$truncate->set_max_length(20);
		$truncate->set_content($content);
		$truncated = $truncate->get_truncated();

		$this->assertEquals($expected, $truncated);
	}

	/**
	* @test
	* @testdox Should add line breaks at the end
	*/
	public function should_add_line_breaks()
	{
		global $content, $expected;

		$truncate = new Omniglot_Truncate_HTML();
		$truncate->set_length(80);
		$truncate->set_max_length(100);
		$truncate->set_content($content);
		$truncated = $truncate->get_truncated();

		$this->assertEquals($expected, $truncated);
	}

	/**
	* @test
	* @testdox Should split long content into arrays
	*/
	public function should_split_into_array()
	{
		global $content;

		$truncate = new Omniglot_Truncate_HTML();
		$truncate->set_length(80);
		$truncate->set_max_length(100);
		$truncate->set_content($content);
		$splitted = $truncate->get_splitted();

		$this->assertIsArray($splitted);
	}

	/**
	* @test
	* @testdox We should get the same string after joining the splitted arrays
	*/
	public function should_get_same_string_after_join()
	{
		global $content;

		$truncate = new Omniglot_Truncate_HTML();
		$truncate->set_length(80);
		$truncate->set_max_length(100);
		$truncate->set_content($content);
		$splitted = $truncate->get_splitted();

		$final = '';

		foreach ($splitted as $split) {
			$final .= $split;
		}

		$this->assertEquals($final, $content);
	}

	/**
	* @test
	* @testdox Should not split the original content if the plain text is shorter than or equal to the maximum length, return the whole text
	*/
	public function should_not_split_the_original_content()
	{
		$content  = '<p>hello world</p><p>My name is shamim al mamun<br>this is another paragraph</p>';
		$expected = '<p>hello world</p><p>My name is shamim al mamun<br>this is another paragraph</p>';

		$truncate  = new Omniglot_Truncate_HTML();
		$truncate->set_length(10);
		$truncate->set_max_length(62); // 62 is the total length of the given content without the tags
		$truncate->set_content($content);
		$splitted = $truncate->get_splitted();

		$this->assertIsArray($splitted);
		$this->assertEquals(1, count($splitted));
		$this->assertEquals($expected, $splitted[0]);
	}

	/**
	* @test
	* @testdox Should get the same content after joining the splitted string
	*/
	public function should_get_same_string_after_joining()
	{
		global $content;

		$length = 1500;
		$max_length = 2000;

		$truncate = new Omniglot_Truncate_HTML();
		$truncate->set_length($length);
		$truncate->set_max_length($max_length);
		$truncate->set_content($content);

		$splitted = $truncate->get_splitted();
		$final = '';

		foreach ($splitted as $index => $split) {
			$final .= $split;
		}

		$this->assertEquals($content, $final);
	}

	/**
	* @test
	* @testdox Should get same string after joining all combinations of length and max_length
	*/
	public function should_get_same_string_after_joining_all_combinations()
	{
		global $content;

		$length = 0;
		$max_boundary = 250;
		$boundaries = array();

		while ($length <= $max_boundary) {
			$max_length = $length;

			while ($max_length <= $max_boundary) {
				$boundaries[] = array('length' => $length, 'max_length' => $max_length);

				$max_length = $max_length + 1;
			}

			$length = $length + 1;
		}

		// $this->assertEquals('31626', count($boundaries));

		foreach ($boundaries as $boundary) {
			$truncate = new Omniglot_Truncate_HTML();
			$truncate->set_length($boundary['length']);
			$truncate->set_max_length($boundary['max_length']);
			$truncate->set_content($content);

			$splitted = $truncate->get_splitted();

			$final = '';

			foreach ($splitted as $split) {
				$final .= $split;
			}

			$this->assertEquals($final, $content);
		}
	}

	/**
	* @test
	* @testdox Should get the given random string after joining the splitted string
	*/
	public function should_get_same_random_string_after_joining()
	{
		global $random_content;

		$length = 1500;
		$max_length = 2000;

		$truncate = new Omniglot_Truncate_HTML();
		$truncate->set_length($length);
		$truncate->set_max_length($max_length);
		$truncate->set_content($random_content);

		$splitted = $truncate->get_splitted();
		$final = '';

		foreach ($splitted as $index => $split) {
			$final .= $split;
		}

		$this->assertEquals($random_content, $final);
	}

	/**
	* @test
	* @testdox Should get the gutenberg post content after joining the splitted string
	*/
	public function should_get_gutenberg_post_content_after_joining()
	{
		global $gutenberg_post_content;

		$length = 1500;
		$max_length = 2000;

		$truncate = new Omniglot_Truncate_HTML();
		$truncate->set_length($length);
		$truncate->set_max_length($max_length);
		$truncate->set_content($gutenberg_post_content);

		$splitted = $truncate->get_splitted();
		$final = '';

		foreach ($splitted as $index => $split) {
			$final .= $split;
		}

		$this->assertEquals($gutenberg_post_content, $final);
	}
}
