# Simwp

This module is meant to be put inside any of your Wordpress themes or plugins to help interacting with Wordpress admin dashboard more easily.

**NOTE:** For non-composer user, you can download a compiled version at [simwp-compiled](https://github.com/dumday/simwp-compiled)

```
composer require simwp=dev-master
```

**Table of Contents :**

1. [Build `Wordpress setting pages`](#build-wordpress-setting-pages)
2. [Work with `Wordpress options`](#work-with-wordpress-options)
3. [Create `Wordpress setting fields` in seconds](#create-wordpress-setting-fields-in-seconds)
4. [Work with `Wordpress notices`](#work-with-wordpress-notices)
5. [Need helps](#need-helps)
6. [FAQ](#faq)
7. [License](#license)

## Build Wordpress setting pages

Register your first own `custom menu` that will contain all your setting pages

```php
// create an admin holder to hold custom menus
// and provide features like auto translating
$admin_holder= Simwp::admin('custom admin');

// your new menu item
$custom_menu = $admin_holder->menu('custom menu');
```

Register a `custom page` setting page and pushed it into `custom menu`

```php
$custom_page = $custom_menu->page('custom page');
```

Register your section `Simple` to be displayed in `custom page`

```php
$custom_page->append(Section_Simple::class);
```

Then each sections will do their own jobs, rendering setting fields.

![wordpress sections are auto-rendered by Simwp](https://i.imgur.com/2Ykq2G9.pngg)

## Work with Wordpress options

Instead of coding those lines and much more

```php
if(wp_verify_nonce($_POST['_nonce'], 'nonce-name') && current_user_can('manage_options')){
	if(isset($_POST[$key]) {
		if(is_string($_POST[$key])){
			...
```

You can let Simwp handles all the Wordpress `option` for you

```php
// Tell Simwp to auto sanitize and update the option for you when user hit enter
// and show error messages when the submitted value is not valid
Simwp::manage('opt-key');

// Check if user submit option value, if so, sanitize it
Simwp::updated('opt-key', $opt_callback);

// Check if user submit option value, and if that value
// is valid with Symfony\Validator, or accessible for current
// section / page
Simwp::handled('opt-key', $opt_callback);

Simwp::is('csrf'); // check if csrf attack presents in admin dashboard, good for ajax options
Simwp::is('dashboard'); // check if current view is admin dashboard
Simwp::is('user'); // check if user logged in

// Some simple option handling
Simwp::option('opt-key')
	->append(Simple_Section) // the option updating only available in a specified section
	->append($custom_menu) // or shared with all the pages inside menu
	->append('themes.php') // or shared with an admin-dashboard slug
	->validate(new Assert\NotBlank()) // force option not to be blank
	->validate(new Assert\Email()); // and force it to be an email
```

![Wordpress options are validated by Symfony\Validator](https://i.imgur.com/Efd3fDw.png)

For getting option value
```php
Simwp::get('opt-key') // get option value

Simwp::option('opt-key')
	->default('def-value') // default value to return when option not found
	->type('array') // submited value will be parsed as array
	->type('boolean'); // submited value weill be parsed as boolean
```

## Create Wordpress setting fields in seconds

An example code of a `section`

```php
class SimpleSection extends Simwp\Section {
	function is_registered(){
		// create a checkbox for 'registered' option
		// short version of $this->view('toggle')->render('registered')
		$this->toggle('registered');
	}

	function user_name(){
		// create an input to fill 'followed-users'
		// short version of $this->view('tags')->render('followed-users')
		$this->tags('followed-users');
	}
}
```

![Some section components](https://i.imgur.com/JAQRFbh.png)

More and more components are being created, current list is default components :

- Checkboxes
- Color Picker
- Date Picker
- Image
- Input
- Lines
- Options
- Radios
- Tags
- Textarea
- Toggle

## Work with Wordpress notices

Create a new `notice` and give it a name

```php
Simwp::notice('simple-notice')
	->append('example text');
```
There are 4 types of notice :
- `no-control` notices are interactive
- `dismissible` notices can be hidden by users, but can be shown at next requests
- `removable` notices can be avoided by users at any requests
- `force` notices can never be avoided by users

And 5 flags of notice : `primary` `info` `success` `warning` `error`

![Notices have 4 types and 4 flags](https://i.imgur.com/BZzhvgT.png)

## Need helps

Find resolved issues or create a new one if you can't find any issue related to your problem at [Issues](https://github.com/dumday/simwp/issues)

## FAQ

**How can I use default Wordpress or my own styles instead of this one's seasoned material design fields?**

Wow I feel sad to hear that. But you can always disable this feature or even add your own styles to components by settings your `admin holder` 's className like

```php
// $admin_holder = Simwp::admin('admin holder')
$admin_holder->className('my-own-class'); // default is 'simwp-material-ui'
```

**How can I create my own section components and filters?**

You can declare you own components and filters at any time, the components should be inside namespace `Simwp\Form` and the filters should be inside namespace `Simwp\Form\Filter` so the default `view` and `filter` method can easily recognize them.

For example, you declare a new component `Simwp\Form\Custom` and inside a `section` you can call this line to render your new custom component :

```php
$this->custom('opt-name');
```

## License

This module is completely **free** for any projects including commercial ones, and based on **MIT License**. You can do anything with it. The project is in its very early state and need more contributed tests, components, filters, or translations ... so I will appreciate very much if you can join us. Thank you.

Stay tuned.
