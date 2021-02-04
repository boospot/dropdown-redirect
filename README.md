# dropdown_redirect
DropdownRedirect for WordPress Plugin Boilerplate with Namespace


Changes to Made:

1. Rename files in:
    * `/dropdown_redirect.php`
    * `/languages/dropdown_redirect.pot`
2. Run Search Replace with **Preserve Case**:
    * @plugin For Plugin Name `dropdown_redirect => YourPluginName`
    * @git_author For Github Author: `boospot => YourAuthorName`
    * @author For Author Name: `Rao => YourName`
    * @author For email: `rao@booskills.com => YourEmail`
    * @link For Link: `https://booskills.com/rao => YourLink`
    * Update Plugin Comment Block in main file `/dropdown_redirect.php`
3. After Adding more files as you go, use composer to update autoload if you need to. You shall need to have composer installed on your computer. In Terminal in the plugin directory, run following:
    *  `composer update`
 