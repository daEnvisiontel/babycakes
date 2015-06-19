==========================================================
== RETAIL MENU CARDS Plugin Usage Instructions ===========
==========================================================

Plugin Author: Adrian Diaconescu
Plugin URL: http://canvasthemes.net/plugins/retail-menu-cards

For a video walkthrough and other tips and tricks, make sure you check the URL above, where I also cover all the info mentioned below.

For support, please contact me via my profile page on Code Canyon:
 - http://codecanyon.net/user/rubiqube

=============================
== Installation =============
=============================

1. Unzip the file downloaded from Code Canyon
2. Upload the "retail-menu-cards" folder to /wp-content/plugins/
3. Navigate to the WordPress Plugins page and click "Activate" on "Retail Menu Cards"

=============================
== Configuration ============
=============================

There are a few basic settings that you may use to customize the appearance of the Menu Cards.

The settings page is located at "Settings > Retail Menu Cards" from your WordPress Dashboard.

 == Display ==
 
	- Choose what the thumbnail links to. This value may be overwritten by short code attributes.
	- Choose what the title links to. This value may be overwritten by short code attributes.
	- Disable different elements of a menu item: thumbnail, title, description, price, labels
	
 == Currency ==	
 
	Define the currency to use and where to position the symbol.
	
 == Styling ==
 
 	- Choose the look and feel, as well as the layout (display using two or three columns). Both values may be overwritten by short code attributes. 
	- Enter any custom CSS here you wish. This is primarily for advanced users and those wishing to modify the default layout.
	

=============================
== Short Codes ==============
=============================

All Menu Cards may be displayed using WordPress short codes. These are entered into post/page content and are very simple to use.

To display a list of all menu cards with default layout options, use:

[rmc-menu]

This will display a list of Menu Items, sorted by Menu Cards.

The short code accepts a variety of parameters.	
	- menu
	- heading
	- thumblink
	- titlelink
	- layout
	- look
	
The syntax is: [rmc-menu menu="14" heading="no" thumblink="image" titlelink="post" look="classic" layout="2col"]

"menu" accepts a list of menu card separated IDs. You can determine a menu card's ID at Menu > Cards, by rolling over a menu card link and looking at the number after "tag_ID=" in the browser status bar.

"heading" accepts "yes" or "no".

"thumblink" accepts "image" (links the thumb to the large version of the image), "post" (links to the single post page) and "none" (no link at all).

"titlelink" accepts "image" (links the title to the large version of the image), "post" (links to the single post page) and "none" (no link at all).

"layout" accepts "1col", "2col" and "3col".

"look" accepts "classic", "list", "grid" and "light".

Go ahead and use the syntax mentioned above to experient with these attributes.

=============================
== Widget ===================
=============================

Retail Menu Cards comes with one custom widget called "RMC Menu Items". It allows you to display a list of menu items, no matter what menu cards they belong to.

You can define a widget title (or leave this empty to not show a widget title), choose one or more menu items to show (use Ctrl + click to select multiple items), as well as choose what the thumb and title point to.