Extracted Aug 2, 2013

From:
[http://www.marcopace.it/blog/2012/12/fuelphp-i18n-internationalization-of-a-web-application](http://www.marcopace.it/blog/2012/12/fuelphp-i18n-internationalization-of-a-web-application)

Converted to Markdown by [http://www.aaronsw.com/2002/html2text/](http://www.aaronsw.com/2002/html2text/)

---

# FuelPHP: i18n, internationalization of a web application

Posted on December 3, 2012, 1:09 pm in:
[PHP](http://www.marcopace.it/blog/categories/PHP),
[FuelPHP](http://www.marcopace.it/blog/categories/FuelPHP)

We have a total of [No Comments](http://www.marcopace.it/blog/2012/12/fuelphp-
i18n-internationalization-of-a-web-application#disqus_thread).

Working with large web application means paying attention to many factors like
security, scalability, performance. There's another one that is usually not
considered or is underestimated: internationalization.

Internationalization, usually&nbsp_place_holder;shortened to "**I18N**"
(meaning "I - eighteen letters - N"), is the process of designing, programming
and implementing products and services so they can be easily
adapted&nbsp_place_holder;to various languages and regions without engineering
changes.

This approach involves a great deal of attention in the development of 3 main
area of a web application:

  1. Translation
  2. Number formatting
  3. Date and time formatting

This tutorial will cover only the first point, that is the most difficult, but
before starting it is necessary to understand how it can be performed: how can
I know which language do the user wants?

&nbsp_place_holder;

#  Introduction to "locale"

When we have to work with internationalization there is a term you come across
very often: **locale**.

With the term _locale_ we identify a group of people having a common set of
requirements for the representation of data: it should be
a&nbsp_place_holder;country, a region or simply&nbsp_place_holder;a community
speaking a common language, so it isn't&nbsp_place_holder;related to the
user's home country.

Locale is usually identified by a string that is created from 2 different ID:

  1. A _language ID_, that represent the lenguage of the user
  2. A _region ID_, that represent the region of the user so the main language can be a little different

An example of it is the locale "**en_us**": it is created from the language
"_en_" (English) and the regione "_us_" (United States). A powerful library
should be able to modify itself regarding both language and region, in this
tutorial we will learn hot to work with languages, although it would be easy
adding support for regions.

Now that you have a little background about locale and localization, its time
to find a way to get the current locale of a user.

There are a lot of way to achieve this and my favorite one was posted on the
[FuelPHP](http://fuelphp.com/forums/discussion/1064/uri-class-extended-to-
support-many-languages) forum&nbsp_place_holder;by the
user&nbsp_place_holder;[C.K.Y.](http://fuelphp.com/forums/profile/200/C.K.Y):
it works perfectly with the last version of FuelPHP (1.4), but I don't know if
it is currently supported by the author.

The code is very simple and can be implemented in a few steps:

  * create the URI class extension
  * add the URI class extension to the autoloader
  * define locale in the config.php
  * add language value to the url
  * how to use a language file
  * create a language file

&nbsp_place_holder;

#  URI Class extension

First of all we have to extend the URI class, so let's do it:

    
    
    <?php
    
    class Uri extends Fuel\Core\Uri
    {
        public function __construct($uri = NULL)
        {
            parent::__construct($uri);
            $this->detect_language();
        }
    
        public function detect_language()
        {
            if ( ! count($this->segments))
            {
                return false;
            }
    
            $first = $this->segments[0];
            $locales = Config::get('locales');
    
            if(array_key_exists($first, $locales))
            {
                array_shift($this->segments);
                $this->uri = implode('/', $this->segments);
    
                Config::set('language', $first);
                Config::set('locale', $locales[$first]);
            }
        }
    }

This class is called togheter with the fuel core class, so every time a user
type an url the "_detect_language()_" method try to take the current language
from the URL, overwriting the original method. If the current language is
currently supported by the web application it is setted as default language
for the current request, otherwhise we can use the default language setted in
the _config.php_.

Copy the code and save it as **fuel/app/classes/extension/uri.php** : I know,
FuelPHP basic installation hasn't an "extension" folder: I suggest you to
create it and to put in every extension to the core, so your web application
will be very tidy.

&nbsp_place_holder;

#  Add URI class to the Autoloader

Very simple: modify your bootstrap.php file with these line of code, so your
extension will be loaded every time and will catch every request to manage the
localization.

    
    
    Autoloader::add_classes(array(
        // Add classes you want to override here
        'Uri' => APPPATH.'classes/extension/uri.php',
    ));

Now your application will work with the current language in the url without
modify your application controller.

An example of the url:

  * http://www.domain.com/**{lang}**/controller/action/value/

&nbsp_place_holder;

#  Define locale in the config.php

Now it's time do define the locale for our application:&nbsp_place_holder;

    
    
    return array(
        'language'           => 'en', // Default language
        'language_fallback'  => 'en', // Fallback language when file isn't available for default language
        'locale'             => 'en_US', // PHP set_locale() setting, null to not set
        'locales'            => array(
            'en' => 'en_US',
            'it' => 'it_IT'
        ),
    );

Here we can find the localization's configuration, so be careful with it. A
little description:

  * **language **is the default language of the application
  * **language_fallback** is the language will be used in case the default one wasn't found
  * **locale** is the current locale, used for the "_set_locale()_" PHP function. It can be null
  * **locales** are the locale supported by the application, you can use every locale (language + region) you want and force them to use a particular language

In this example I've used two locale, it_IT for my first language, italian,
and en_US for the english version of the application.

The english is the primary language, of course.

&nbsp_place_holder;

#  Add language value to the URL

All works well, but there is another things to do: how can we insert the
language in the url?

The easiest way is to use a wrapper around the "_Uri::create()_" method, so
I've created a method named "_generate()_".

So its time to reopen again the file **fuel/app/classes/extension/uri.php**
and put in this method:

    
    
    public static function generate($uri = null, $variables = array(), $get_variables = array(), $secure = null) 
    {
        $language = Config::get('language');
    
        if ( !empty($uri))
        {
            $language .= '/';
        }
    		
        return \Uri::create($language.$uri, $variables, $get_variables, $secure);
    }

Now we can create an url using the syntax _Uri::generate('mylink')_.

&nbsp_place_holder;

#  Using language file

We are at the end of the tutorial: now our application is able to understand
the language selected by the user, so its time to **create** an application
that can be multilanguage.

This can be done using the **Lang** class of the Fuel core, using two simple
methods:

  * **load()** can get all the entries of a language file and put it in a variable. An example is:  
&nbsp_place_holder;&nbsp_place_holder;&nbsp_place_holder;
Lang::load('example', 'test');

This will load the **fuel/app/lang/{lang}/example.php** file in the "**test**"
variable.

  * **get() **can get the translated value associated to the current key:&nbsp_place_holder;&nbsp_place_holder;&nbsp_place_holder;  
&nbsp_place_holder;&nbsp_place_holder;&nbsp_place_holder;
Lang::get('test.something');

It will take the "**something**" key from the "**test**" variable, the same we
loaded with the **load()** method.

Now we are able to get the current language file, get the variable... What
else?

&nbsp_place_holder;

#  Create language file

We are at the end of the tutorial: the creation of a language file.

Let's create a file called **fuel/app/lang/{lang}/example.php **: {lang} is
the language, example.php is the file, but the name should be whatever you
want. I suggest to create a file for every section of the site.

Now its time to create it:

    
    
    return array(
        'hello' => 'Hello',
        'something' => 'brave new :name!',
        'test' => array('key1' => 'variable1')
    );
    

And these are examples to how use it:

    
    
    echo Lang::get('test.hello');
    // This will print "Hello"
    
    echo Lang::get('test.something', array('name' => 'world'));
    // This will print "brave new world"
    
    echo Lang::get('test.test.key'));
    // This will print "variable1"

If you need more detail about the Lang class, please refer to the
[documentation](http://fuelphp.com/docs/classes/lang.html).

I hope you can find some answers to your questions.

Enjoy!