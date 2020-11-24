<?php
/*
Plugin Name: Pinpoint Booking System PRO
Version: 2.9.4
Plugin URI: https://pinpoint.world/wordpress-booking?utm_source=WordPress&utm_medium=plugin%20pro
Description: Transform your WordPress website into a booking/reservation system. If you like this plugin, feel free to rate it five stars <a href="https://pinpoint.world/shop/reviews/pinpoint-booking-system-wordpress-plugin-pro-version-1?utm_source=WordPress&utm_medium=plugin%20pro" target="_blank">here</a>. If you encounter any problems please visit our <a href="https://pinpoint.world/wordpress-booking/support?utm_source=WordPress&utm_medium=plugin%20pro" target="_blank">Support Forum</a> so we can help you.
Author: PINPOINT.WORLD
Author URI: https://pinpoint.world/wordpress-booking?utm_source=WordPress&utm_medium=plugin%20pro
*/

/*
Change log:
 
        2.9.4 (2019-10-30)
                
                * "Extras" information for reservations are added to the Excel export.
                * Search by location bug has been repaired.
                * The SMS |DETAILS| tag data has been modified to fit in 3 SMSes (reservation ID, calendar name and selected language have been removed). 
                * Total price when it is 100% discounted will be displayed as 0 to the customer.

	2.9.3 (2019-09-16)
                
                * Support for Strong Customer Authentication (SCA) has been added.
        
        2.9.2 (2019-08-02)
 
		* Translations for Spanish, French and German languages have been added.
 
        2.9.1 (2019-07-01)
 
                 * New translated words that contain single quotes (') are saved correctly, bug repaired.
                 * The available status text will hide if the calendar day is too small for it to fit.
                 * The starting price of a selection in the search will correctly display the minimum price for that day instead of the minimum price of the entire calendar.
 
        2.9.0 (2019-05-14)
 
		* Reservations calendar-view no longer gets stuck when "Loading data", bug repaired. 
		* Reservations that contain single or double quote characters (', ") in the contact form can be edited, bug repaired.
		* Small change to countries phone prefix list.
		* Text no longer contains multiple forward slashes (\) before single and double quote characters (', "), bug repaired.
 
        2.8.9 (2019-04-11)
 
		* A booking request with the number of items bigger than one of the hours selected can not be created anymore, bug repaired.
		* A new field for email sender for admin notifications has been added. 
		* Extras price always multiplied by the number of booked of items when adding a reservation from back end, bug repaired.
 
        2.8.8 (2019-03-06)
 
                * Compatibility issue with WordPress 5.1 has been repaired.
  
        2.8.7 (2019-02-25)
                
                * "No services available" error does not appear after you view a calendar redirected from search and you successfully complete a reservation, bug repaired.

        2.8.6 (2019-01-31)

		* Changing the contact information for a reservation will correctly update the user email, bug repaired.
		* Some typos have been corrected.
		* The compatibility with other plugins that previously caused a fatal error on plugin activation has been repaired.
 
        2.8.5 (2018-12-14)

                * Decimal deposits (fixed and percentage) are correctly calculated and displayed, bug repaired.
                * Number of hours available (per day) now correctly includes "special" status, bug repaired.
                * Reservation calendar now lets you edit the status of a WooCommerce reservations, bug repaired.
 
        2.8.4 (2018-12-03)
                
                * Reservations export contains a column with the status of the reservation and the date you created the export file.
                * Reservations with status expired, pending and rejected can be exported, bug repaired.
                * The option to add your PINPOINT.WORLD referral ID and display a link with it under the calendar has been added.
     
        2.8.3 (2018-10-29)
                
                * "Book now" button does not appear after selecting hours from the sidebar in calendars without default availability, bug repaired.
                * Canceling a reservation with decimal pricing restores the correct price and availability for day/hour, bug repaired.
                * Clickatell SMS Platform account compatibility has been added.
                * First hour interval of the day without start/finish hours enabled on custom availability can be selected in front end and back end, bug repaired. 
                * SMS templates have been added.
   
        2.8.2 (2018-10-08)
 
                * Default country can be selected for the phone form field.
                * Hour intervals are displayed correctly if you have "Use start/finish hours" disabled, bug repaired.
                * Minor CSS improvements for the front end calendar sidebar.
                * More than 2 emails can be saved for CC and BCC, bug repaired. 
                * The availability number in days that have grouped hours is displayed correctly, bug repaired.
                * The selected country for the phone field in front end will now display the phone prefix instead of country name.
  
        2.8.1 (2018-09-15)
                
                * A reservation with a booking stop rule can not be submitted if you leave the calendar page open for a few hours and try within the 'x minutes in advance' timeframe, bug repaired.
                * Default availability for hours now displays the correct number of hours and per day, bug repaired.
                * The current day will display the correct number of hours that are still available, including the case when you have a booking stop rule enabled, bug repaired.
                
        2.8.0 (2018-08-13)
  
                * Booking unavailable hours after changing the date from an available hour bug has been repaired.
                * Correct currencies for Kazakhstan Tenge and Namibia Dollar are now displayed, bug repaired.
                * Isolated case when you could not book more than 2 weeks on default availability has been repaired.
                * Minor search functionality has been repaired.
 
        2.7.9 (2018-06-26)
 
                * Back end hours reservations on default availability can be created, bug repaired.
		* Documentation was updated.
                * PHP 7.2 compatibilities have been fixed.
                * Some WooCommerce compatibilities have been fixed.
                * Templates will be recognized in a child theme, bug repaired.
 
        2.7.8 (2018-05-22)

                * Deleting a rule in use does not prevent you from booking, bug repaired.
                * Grouped hours can not be individually selected anymore, bug repaired.
		* Reservation UID for iCal synchronization is generated differently.
                * Selecting the timezone adds correct hours from Google Calendar reservations, bug repaired.
                * Small bug on Mozilla Firefox when trying to add a reservation without selecting a calendar has been repaired.
                * WooCommerce overlapping error message is not displayed after you make a reservation with a day with default availability, bug repaired.
        
        2.7.7 (2018-04-17)

                * Phone number country prefix is saved and displayed in reservations, bug repaired.
                * Small bug when adding a reservation from back end has been repaired.

        2.7.6 (2018-04-10)

                * Calendar is displaying the correct selection when being redirected from the search result, bug repaired.
                * Calendar is loading correctly when being redirected from the search results, bug repaired.
                * Coupon check in date and start hour validation bug has been repaired.
                * Map marker link is saved correctly after changing the style template, bug repaired.
                * The search now displays all available calendars before making any selection from the sidebar options, bug repaired.
                * The sidebar is displaying the number of booked items when the option to use start/end date was disabled and a rule for a minimum booked items number was set, bug repaired.
                * When the number of booked items is lower than the minimum number available set in the calendar settings, the number available will be booked instead of creating a reservation with 0 booked items, bug repaired. 

        2.7.5 (2018-03-19)

		* "Check availability" translation for the search widget has been repaired.
		* All days are properly displayed in front end and back end calendar, bug repaired.
		* All reservations made with an IPv6 address are now saved correctly, bug repaired.
		* The IP addresses from booking requests are no longer saved in the database, because of incompatibilities with devices that have only IPv6 enabled.

        2.7.4 (2018-02-13)

		* HTML tags from email templates are saved correctly, bug repaired.
		* iCal links are saved correctly, bug repaired.
		* Overlapping intervals can't be added in the WooCommerce cart, bug repaired.
		* Settings database is updated correctly from version 2.6.3, bug repaired.

        2.7.3 (2018-01-31)

                * Default schedule is reset correctly, bug repaired.
		* Reservations are displayed when all calendars are selected, bug repaired.
		* Reservations for multiple consecutive days with different availability are now correctly updating the schedule, bug repaired.

        2.7.2 (2018-01-19)

		* Grouped days with "morning check out" option enabled can be booked, bug repaired.
		* Last hour available can be selected, bug repaired.
                * Loading time has been improved, bug repaired.
 
        2.7.1 (2018-01-15)

                * A sanitization function for all $_POST and $_GET variables has been added.

        2.7.0 (2018-01-14)

                * Security bugs repaired.

        2.6.9 (2018-01-13)

                * Security bug repaired.

        2.6.8 (2017-12-18)

		* Front end calendar availability check works when users are logged out, bug repaired.

        2.6.7 (2017-12-16)

		* 1st step for new framework integration has been done.
		* Availability indexes has been changed 100%.
		* Availability is set by following priorities: rules, schedule, default schedule.
		* Booking requests are not possible anymore for unavailable hours, bug repaired.
		* Reservations after a booked period can be approved, bug repaired.
		* Search feature has been repaired.
		* The .xls export for the reservations displays data in correct column, bug repaired.
		* The schedule is shown correctly according to the calendar rules, bug repaired.

        2.6.6 (2017-11-17)

		* Calendars in back end can be selected on touchpads, bug repaired.
		* Coupons do not show in calendar settings after they have been disabled, bug repaired.
		* Global variables are used correctly when the server has PHP 7.0 installed, bug repaired.
		* iCal file link is protected with the API key.
		* Search by number of items has been repaired.
		* Synchronisation with systems that use iCal files, like Google Calendar & Airbnb, has been redone. Two way synchronization, days and hours can be used, reservations are saved to PBS, overlap is verified when a new reservation is added ...
		* WooCommerce reservations with morning checkout do not overlap in cart, bug repaired.

        2.6.5 (2017-10-11)
		
		* Filtering reservations by hours has been repaired.
		* Reservations can only be made for available days, bug repaired.
		* Search function is compatible with WPML.

        2.6.4 (2017-09-28)
		
		* "Close" button has been removed from default availability form.
		* A reservation can not be requested for days with status "none", bug repaired.
		* Airbnb and Google Calendar synchronization bug for reservations with "cancelled"," pending" and "deleted" statuses has been repaired.
		* Coupons do not show in the front end calendar after being disabled, bug repaired.
		* One way synchronization, for the hours, from Google Calendars to Pinpoint Calendars, has been added.
		* The bug that prevents days in calendars to be displayed when the default availability has been saved wrong in the database has been repaired.

        2.6.3 (2017-09-13)
		
		* Front end and back end calendars display correctly if default availability has been saved wrong in the database, bug repaired.
		* The user that duplicates a calendar becomes the owner of it, bug repaired.

        2.6.2 (2017-09-11)
		
		* Excel empty columns are now decalated to display the information in their place.
		* Google Fonts are now loaded using https.

        2.6.1 (2017-09-05)
		
		* Discount has been repaired for hours intervals with "Hours breaks" option enabled.
		* Search widget has been added.

        2.6.0 (2017-08-12)
		
		* Availability default status has been set back to 'none'.
		* Availability number is displayed correctly, for days and hours, in calendars that have default availability set.
		* Bugs that appear when you add a reservation in back end have been repaired.
		* Coupons valid period bug has been repaired.
		* Hours availability can be set, bug repaired.
		* iCalendar synchronization bug with calendars that have morning check out option enabled has been repaired. 
		* Once you approve reservations in back end, the availability will not overlap, bug repaired.
		* Reset availability bug has been repaired.
		* Search function works with calendars that have default availability set.
		* Style templates can be overwritten from a WordPress theme if you add the CSS files in [theme-folder]/dopbsp-templates folder.


        2.5.9 (2017-07-20)
        
		* Default availability for hours bug has been repaired.

        2.5.8 (2017-07-18)
        
		* Default availability has been added.
		* Search price filter bug has been repaired.
		* WooCommerce language bug has been repaired.

        2.5.7 (2017-06-19)
        
		* iCal synchronization bug has been repaired.
		* The option to set minimum availability has been repaired.
		* Translation text for June and July months has been repaired.
		* WPML compatibility with chinese han language has been added.

        2.5.6 (2017-06-14)
        
		* iCal synchronization bug has been repaired.
		* The option to hide availability has been added.
		* The option to set minimum availability has been added.

        2.5.5 (2017-06-02)
        
		* Coupons are displayed when only extras have price.
		* Email notifications bug has been repaired.

        2.5.4 (2017-05-25)
        
		* IP address has been added to the exported data.
		* More shortcodes have been added in email and SMS notification.

        2.5.3 (2017-05-23)
        
		* Availability number for hours interval has been repaired.
		* Availability number for days has been repaired.
		* Front end language bug has been repaired.
		* Shortcodes from search texts are removed.

        2.5.2 (2017-05-16)
        
		* Availability number for days with hours has been repaired.
		* WPML compatibility with norwegian language has been added.

        2.5.1 (2017-05-05)
        
		* Coupons validation bug has been repaired.
		* Front end calendar auto select bug has been repaired.
		* WooCommerce check out deposit bug has been repaired.
		* WooCommerce cart deposit bug has been repaired.

        2.5.0 (2017-04-24)

		* Google calendar hours synchronization bug has been repaired.
		* Multiple coupons validation bug has been repaired.
		* Search AM/PM bug has been repaired.
		* Search for hours bug has been repaired.
		* Search for same day bug has been repaired.
		* Terms and conditions bug has been repaired.
		* WPML compatibility with portuguese language has been added.

        2.4.9 (2017-04-07)

		* WooCommerce 3.0 compatibility has been added.
		* WooCommerce reservations notifications bug has been repaired.

        2.4.8 (2017-04-06)

		* Amount precision calculation bug has been repaired.
		* Billing address bug has been repaired.
		* Clickatell.com SMS notifications bug has been repaired.
		* Shipping address bug has been repaired.
		* The forms text bug has been repaired.

        2.4.7 (2017-03-25)
 
		* Auto selection of period in search function bug has been repaired.
		* Google calendar synchronization with hours bug has been repaired.
		* WooCommerce cart text bug has been repaired.
		* WPML portuguese language compatibility has been added.

        2.4.6 (2017-03-13)
 
		* Clickatell.com SMS notifications are sent using Central API.
		* Hours definitions NULL bug has been repaired.
		* Search translation bug has been repaired.
		* The schedule is displayed correctly for different characters, bug repaired.
		* Unselected coupons are not displayed in front end, bug repaired.
		* You can add an API key for Google maps.

        2.4.5 (2017-03-02)
        
		* Front end translation bug has been repaired.
		* WooCommerce shop page translation bug has been repaired.

        2.4.4 (2017-02-22)
        
		* A calendar is duplicated correctly, bug repaired.
		* Modifications to the code can be added in Pinpoint Booking System Extend plugin.
		* Reservations done from WooCommerce work correctly now, bug repaired.
		* Translation is loading for each section: back end, front end, WooCommerce front end, calendar, ...
		* WPML compatibility has been added.

        2.4.3 (2017-01-18)
        
		* "Pay full amount" message bug has been repaired.
		* Airbnb synchronization bug has been repaired.
		* Contact details from WooCommerce  are added in Pinpoint reservations.
		* Google calendar synchronization bug has been repaired.
		* Hour intervals can be created separate between them.
		* The compatibility with Redsys Woocommerce plugin has been added.

        2.4.2 (2016-12-20)
        
		* "Pay full amount" option for deposits is compatible with WooCommerce.
		* "Pay full amount" option for deposits can be enabled or disabled.
		* Export to ICS option for reservations has been added.
		* Front end coupon messages bug has been repaired.
		* Front end loader bug has been repaired.
		* Front end warning messages bug has been repaired.
		* Multiple coupons can be used in same calendar.
		* Synchronization with Airbnb has been added.
		* Synchronization with calendars that use iCalendar has been added.
		* The text in WooCommerce products list is displayed correctly, bug repaired.

        2.4.1 (2016-12-12)
        
		* "Pay full amount" option for deposits has been added.
		* Hide front end price option has been added.
		* Search for calendars with "morning checkout" option bug has been repaired.
		* Season css class for days has been added.  
		* Synchronization with Google Calendar has been added.
		* The bug when a coupon is not selected has been repaired.
		* The quote character bug from reservation contact informations has been repaired.
		* Time zone for calendars has been added.

        2.4.0 (2016-11-28)

		* "Taxes & Fees" are now compatible with Woocommerce.
		* Calendar availability is set correctly for future years, bug repaired.
		* Coupons bug in front end calendar has been repaired.
		* Datepicker don't interfere with mobile keyboards, bug repaired.
		* Deposit details are now available in WooCommerce order.
		* Locations autocomplete bug has been repaired.
		* You can now use server's time to display the date and hours in calendars.

        2.3.9 (2016-11-19)

		* "Add to cart" button can be translated, bug repaired.
		* "Add to cart" button is replaced with a loader when you add a reservation in WooCommerce.
		* Extras multiple checkboxes are selected correctly when the field is required, bug repaired.
		* IP address is saved in reservation data.
		* Past hours don't display for the current day, bug repaired.
		* Pending reservations bug has been repaired.
		* The calendar moves to hours section after what a day is selected.

        2.3.8 (2016-11-11)

		* Phone form field has been added.
		* SMS notifications have been added (clickatell.com). 
		* You can set default value to extras options.

        2.3.7 (2016-10-31)

		* Bugs in prototypes have been repaired.
		* Export to CSV option for reservations has been added.
		* Export to Excel option for reservations has been added.
		* Export to JSON option for reservations has been added.
		* Extras can multiply or not with number of booked items.
		* Extras required bug with woocommerces has been repaired.
		* NaN price bug has been repaired.
		* The bug for multiple extras required functionality has been repaired.
		* The search bug for calendars with morning checkout option has been repaired.

        2.3.6 (2016-10-24)

		* Extras required functionality has been changed.
		* Selected hours show correctly in reservations.
		* The discount is calculated correctly when hours are selected, bug repaired.
		* The hours are displayed in calendar only view mode.

        2.3.5 (2016-10-21)
     
		* Contact informations in reservations bug has been repaired.   
		* Print reservations button has been added when you view them with the calendar.
		* The calendars opened from search will have the period automatically selected.
		* The function to load the calendars with period and time selected from $_GET variables has been added.
		* You can add your own Google Maps API key. https://developers.google.com/maps/documentation/javascript/get-api-key

        2.3.4 (2016-10-17)

		* Print the reservations has been added.

        2.3.3 (2016-10-13)

		* Edit for reservation contact informations has been added.
		* The bug for calendar template change has been repaired.

        2.3.2 (2016-10-11)
        
		* Bugs in editor shortcodes widget have been repaired.
		* The "view only info" option for calendars can be set from shortcode.
		* The search bug for calendars with morning checkout option has been repaired.

        2.3.1 (2016-09-29)

		* The compatibility with Woocommerce deposit has been added.

        2.3.0 (2016-08-10)

		* A Javascript callback function after what a booking has been made has been added for some payment systems.
		* Multiple use of payment systems bug has been repaired.

        2.2.9 (2016-07-26)

		* Small bug when updating Pinpoint Booking System has been repaired.

        2.2.8 (2016-07-21)

		* Omnipay library has been added.

        2.2.7 (2016-07-12)

		* The documentation link has been modified.
		* The update link has been modified.

        2.2.6 (2016-07-09)

		* The possibility to share your locations with PINPOINT.WORLD has been added.
 
        2.2.5 (2016-07-07)

		* Duplicate calendars icon has been repaired.
		* Uninstall function has been repaired.

        2.2.4 (2016-05-07)

		* "Coupons" created by other users can be viewed and edited by the booking system's administrators.
		* "Discounts" created by other users can be viewed and edited by the booking system's administrators.
		* "Emails" created by other users can be viewed and edited by the booking system's administrators.
		* "Extras" created by other users can be viewed and edited by the booking system's administrators.
		* "Fees" created by other users can be viewed and edited by the booking system's administrators.
		* "Forms" created by other users can be viewed and edited by the booking system's administrators.
		* "Rules" created by other users can be viewed and edited by the booking system's administrators.
		* Licence activation detects server timeout requests.
		* Reservations that are expired can be sorted by date.

        2.2.3 (2016-04-21)
		
		* PayPal integration has been updated to  to support certificates using SHA-25.
		* Reservations without check out are added on some servers that delete blank post variables. We recommend contacting your hosting company to remove this issue.
		* Translation is displayed correctly in widgets, bug repaired.

        2.2.2 (2016-04-16)
		
		* Reservations with hours are added on some servers that delete blank post variables. We recommend contacting your hosting company to remove this issue.
		* WooCommerce cart object is not being saved to cache when you create a reservation.

        2.2.1 (2016-04-12)
		
		* Hours' reservations don't overlap when you use the WooCommerce cart, bug repaired.
                * The incompatibility with Yoast SEO has been repaired.

        2.2.0 (2016-04-03)
		
                * "Search" settings are saved correctly, bug repaired.
                * Special characters are displayed correctly in infos and notes from the calendar, bug repaired.
 
        2.1.9 (2016-03-29)
		
                * Plugin & "Add-ons" activation has been improved. You can delete an activation from https://pinpoint.world/user/licences/?utm_source=WordPress&utm_medium=plugin%20pro if you have deleted the plugin and you have not deactivated it or if you want to activate it on another domain. Also the notification disappears after what the update is completed.
 
        2.1.8 (2016-03-17)
		
		* Database is no longer accidentally deleted when you update from FREE to PRO version.
		* Reservations are created and the period is booked when a deposit is made via WooCommerce, bug repaired.
		* Small visual modifications to the user permissions page.
 
        2.1.7 (2016-02-23)
 
		* Minimum booking period for hours is correctly validated, bug repaired.
		* The discount is calculated correctly for different rules with same time lapse, bug repaired.
		* The discount is calculated correctly for hour intervals, bug repaired.
		* The hours are displayed in the correct format in search, bug repaired.
 
        2.1.6 (2016-01-21)
                
                * Some updates have been done to the code to meet the PHP7 standards.
                * Validation for hours' availability has been improved.
 
        2.1.5 (2016-01-17)
                
                * Fatal error that appears when WooCommerce is not installed has been repaired.
 
        2.1.4 (2016-01-16)
                
                * Compatibility with some WooCommerce themes has been repaired.
                * The documentation link has changed.
                * The shop's SSL certificate will validate when the licence is activated, bug repaired.
 
        2.1.3 (2015-12-18)
                
                * Custom post page refresh correctly, after a new post is added, bug repaired.
                * PHP warning was repaired, when search returns nothing. 
                * Reservations are added in WooCommerce cart if user is not logged in, bug repaired.
                * Support for "Beautify" theme has been added.
 
        2.1.2 (2015-12-07)
 
                * Add-ons' update class has been improved.
                * New versions are checked and the plugin can be updated from WordPress administration area.
                * The coupon code is not visible in JavaScript anymore, bug repaired.
                * WooCommerce functionalities have been changed. You can more easily attach Pinpoint to an WooCommerce product, variations are not needed anymore, availability is restored when an order is canceled and much more. Integration is much easier with multi vendor plugins types.

        2.1.1 (2015-09-09)
                
                * "Booking System" has been renamed "Pinpoint".
                * API - List reservations added.
                * Classes constructors have been modified to meet the PHP5 standards.
                * Reservations list pagination display the correct reservations, bug repaired.
                * Widgets constructor call has been modified to work in WordPress version 4.3.0 and higher.

        2.1 (2015-06-29)

                * "Add-ons" & "Themes" load data also via cURL.
                * API - Beta version released.
                * Duplicate calendar added.
                * Reservations list pagination added.
                * PayPal small bug repaired.
                * Security bug repaired.
                * WooCommerce "Add to cart" text added in translation.

        2.0.9 (2015-05-19)

                * "Add-ons" activation added to receive automatic updates.

        2.0.8 (2015-02-19)

                * "Add-ons" added. Increase and improve functionalities.
                * "Themes" added. A collection of themes specially created for the booking system.
                * "Settings" database has been updated.
                * "Tools" - "Repair calendars settings" has been updated.
                * "Tools" - "Repair search settings" added.
                * Billing & shipping address added.
                * Calendar sidebar widget CSS has been repaired.
                * Calendars are displayed correctly for the users that have permissions to view them, bug repaired.
                * Email form field can be empty when is not mandatory, bug repaired.
                * Notifications are not sent two times when you use a SMTP, bug repaired.
                * PayPal transaction may be refunded when a reservation is canceled.
                * Plugin update has been optimized.
                * Reservation status is saved correctly in "Reservations calendar", bug repaired.
                * Filter by price, in search, has been repaired.
                * Small "add reservations" CSS style update.
                * The "book now" button is not visible when a payment is in progress, bug repaired.
                * TLS connection can be used with SMTP.
                * User meta is deleted when the plugin us deleted.
                * WooCommerce cart, validation bug has been repaired.
                
        2.0.7 (2015-01-08)

                * Cyrillic characters are displayed correctly in calendars tooltip, bug repaired.
                * Database indexes have been updated.
                * Months are displayed correctly, if you navigate through the calendar after you select a period of time from the sidebar, bug repaired.
                * Old calendars load even if availability is not set, bug repaired.
                * Payment API has been added.
                * PayPal API sandbox credentials have their own settings.
                * PayPal works with both SSL and TLS, until SSL will be dropped.
                * Reservations' count is displayed correctly in "Calendars" section, bug repaired.
                * SMTP notifications are sent correctly for each payment method, bug repaired.
                * The translation is displayed in alphabetical order, when you want to edit it, in WordPress admin.
                * Updating and fixing the translation database has been improved.
                * Users' roles are displayed correctly, even if they are modified by third party plugins.
                * WooCommerce "Add to cart" button can be replaced with booking system button.
                * WooCommerce booking variations are cleaned more often, to improve performance.
                * WooCommerce products can have multiple variations with the booking system, bug repaired.
 
        2.0.6 (2014-12-03)

                * Currencies can be added using hooks.
                * Deposit information is displayed correctly in email notifications.
                * Language in admin is verified and set to default, if different errors appear.
                * Languages can be added using hooks.
                * PayPal API request using TLS instead of SSL.
                * Search display the correct results for calendars that have hours interval enabled, bug repaired.
                * Search results display correct calendars when you search only hours, not days, bug repaired.
                * Selecting quantity for reservation in WooCommerce cart has been disabled, bug repaired (for WooCommerce > 2.2.8).
                * Translation text is created when a new language is enabled, bug repaired.
 
        2.0.5 (2014-11-06)
 
                * Back end list items are displayed correctly if name is blank.
                * Booking requests buttons work in back end reservations calendar, bug repaired.
                * Database class has been updated.
                * Front end calendar can start at any day you want.
                * Front end fonts are loaded from Google using a secure connection (HTTPS).
                * Pending reservations are displayed in back end if there is no payment required, bug repaired.
                * Reservations filters history is saved and is displayed when you revisit the page.
                * Minimum price is displayed correctly when searching for calendars with hours, bug repaired.
                * Total price is calculated correctly when discount is used, bug repaired.
                * Translation can repair itself.
                * WooCommerce variations are hidden in back end, if they are used by the booking system.
 
        2.0.4 (2014-10-28)
                
                * "Locations" added. Create a location and add booking calendars to it.
                * "Search" added. Use this feature to search for availability in multiple calendars.
                * "Tools" added. Tools to help you with some of the booking system needs.
                * All WordPress back end CSS classes are unique.
                * Booking notifications can be sent using Gmail SMTP, bug repaired.
                * Booking notifications can be set to use different methods to send emails to administrators and users.
                * Booking notifications can use two SMTP servers, one for administrators and one for users.
                * Booking notifications for administrators have Cc and Bcc fields, so that you can send them to multiple people.
                * Calendar is displayed correctly in WooCommerce summary, bug repaired.
                * Custom posts display the booking calendar in WordPress admin, bug repaired.
                * Database is created correctly when updating from a version older than 2.0, bug repaired.
                * Discounts can be calculated including Extras price, in booking requests.
                * PayPal payment calculates prices with decimal correctly, bug repaired.
                * PHP function mysql_insert_id() has been replaced with $wpdb->insert_id, in WordPress back end.
                * Retina ready, both front end calendar & back end administration area.
                * Reservations calendar display the reservation correctly in last weekday, bug repaired.
                * Role actions are set correctly for custom user roles, bug repaired.
                * Special characters display correctly in WooCommerce cart, bug repaired.
                * Taxes & fees percent value is correctly calculated from "Extras", in front end calendar plugin, bug repaired.
                * Unpaid reservations do not display in WordPress back end, bug repaired.
                * WooCommerce details display in reservations calendar, bug repaired.
                * WooCommerce reservation do not duplicate when an order is made anymore, bug repaired.
                * WooCommerce reservations with same day but different hours do not overlap anymore, bug repaired.
                * WooCommerce shipping tax is not attached to a booking product anymore, bug repaired.
 
        2.0.3 (2014-08-28)
                
                * "Dashboard" memory tests have been improved.
                * "Dashboard" MySQL test works in PHP 5.5 or higher, bug repaired.
                * Bookings/reservations calendars jump to the last added/removed month.
                * Booking/reservation notifications & payment gateway settings can be edited in custom posts, bug repaired.
                * Currency can be displayed with space when price is shown, both in booking calendars and WordPress back end.
                * Displaying all translation initially in WordPress admin can be disabled in the configuration file.
                * DOP Select jQuery plugin not working with some themes, in front end booking calendar, bug repaired.
                * Information tooltip is displayed, bug repaired in front end booking calendar.
                * Messages modal always hides in WordPress back end, bug repaired.
                * November & December months are displayed correctly in reservations, booking notifications ...
                * Number of days in booking/reservation is calculated correctly in October, bug repaired.
                * PayPal cancel, error, success links are set correctly in front end booking calendar, bug repaired.
                * Price decimals ending in 0 display correctly in back end bookings/reservations and notifications, bug repaired.
                * Reservations calendar has been added/improved, in WordPress back end.
                * Translation may be forced reset, bug repaired.
                * Weekdays are displayed correctly in WordPress back end datepickers, bug repaired.
                * WooCommerce code can be enabled in configuration file, if WooCommerce is not detected.
 
        2.0.2 (2014-08-04)
                
                * "Dashboard" added. Display a landing page and server environment.
                * Administrators are removed from calendar user permissions list, bug repaired.
                * Armenian dram currency added.
                * Bangladesh Taka currency added.
                * Booking notifications can be sent using PHP mail function.
                * Booking notifications can be sent using WordPress wp_mail function.
                * Booking notifications methods can be tested.
                * DOP Select jquery plugin updated.
                * Form data, that was entered when the a booking was requested, can be displayed in calendar information tooltip and/or day/hour body.
                * Set minimum booking period for less than 1 hour, bug repaired.
                * Use different product type in WooCommerce, bug repaired.
                * User booking notifications are not sent to admin, bug repaired.
                * WooCommerce cart & order display the right language for bookings, bug repaired.
                * WooCommerce booking with "Direct bank transfer" error has been repaired.

        2.0.1 (2014-07-25)
                
                * Adding reservations from back end update availability, bug repaired.
                * Bookings can be limited to minutes.
                * Booking notifications are sent in the language that was used when the reservation was created.
                * Booking notifications are sent to multiple admins, bug repaired.
                * Jump to "Add to cart" button in WooCommerce after a reservation has been selected for booking.
                * Kenya Shilling currency added.
                * Period is booked after payment is done with some WooCommerce payment gateways extensions, bug repaired.
                * Set booking period rules for minutes.
                * TinyMCE button incompatibility with some themes has been repaired.
                * Update schedule after a booking request is payed with PayPal, bug repaired.
                * Use prices lower than 1 in a booking request, added.
                * Users permissions for specific calendars have been repaired.

        2.0 (2014-07-22)
 
                * "Coupons" added. Create voucher codes for your clients to use with their booking requests.
                * "Discounts" added. Give discounts for the period booked, in different time periods.
                * "Email templates" added. Customize your booking notifications directly from administration area.
                * "Extras" added. Add amenities, services & other stuff, with price or not, to a booking/reservation.
                * "Rules" added. Currently you can set min/max time lapse for a booking request.
                * "Taxes & fees" added. Set taxes & fees that need to be paid (VAT tax for example) with the booking.
                * "Translation" page has been updated in WordPress admin.
                * Add user permissions using custom roles in WordPress admin.
                * AJAX requests no longer return 403, 404 errors in front end.
                * All algorithms are improved and work faster. Install, save, search ...
                * Availability text is visible on special days, bug repaired.
                * Back end UI/CSS has been changed. A new design has been created for WordPress administration area.
                * Booking notifications are sent without SMTP if SMTP does not work.
                * Booking notifications can be enabled/disabled in administration area.
                * Booking notifications can be sent to multiple admins.
                * Compatibility with PHP 5.3 or higher has been repaired.
                * Complete code core changes. Everything is OOP & commented.
                * Currency can be positioned before or after price, in booking calendar.
                * Current year changes on booking calendar resize, bug repaired.
                * Custom post types do not appear anymore in blog posts by default.
                * Data save/load speed & server memory usage has been optimized.
                * Days availability is restored when you cancel a booking/reservation.
                * Different levels of checking availability have been added in the booking process.
                * Front end booking calendar info messages hide after a few seconds.
                * Front end booking calendar's sidebar view is customizable.
                * Front end booking calendar speed has been improved.
                * Front end UI/CSS has been changed. A new design has been created for front end booking calendar and all classes and ids are unique.
                * IE bugs repaired.
                * Language is not saved anymore in sessions in front end.
                * Language codes have been changed to international codes for: Albanian (al->sq), Basque (bs->eu), Belarusian (by->be), Chinese (cn->zh), Croatian (cr->hr), Czech (cz->cs), Danish (dk->da), Dutch (du->nl), Greek (gr->el), Haitian Creole(ha->ht), Irish (ir->ga), Malay (mg->ms), Maltese (ma->mt), Persian (pe->fa), Spanish (sp->es), Swedish (se->sv), Welsh (we->cy).
                * Languages can be enabled/disabled in WordPress back end.
                * Major database changes. Column changes and more indexes created.
                * Minimum booking period error message does not display randomly when you select only check in date, in booking calendar.
                * Payment transaction ID is displayed in booking notification emails.
                * PHPMailer class is used when sending booking notifications.
                * Redirect after a booking has been made, has been added.
                * Redirect after a booking has been payed with PayPal, has been added.
                * Reservations view is the same after page is refreshed.
                * Possibility to select more than one group of days/hours in a booking/reservation has been added.
                * Required checkbox validation bug repaired, in booking form.
                * Set the number of months to be initially displayed in the booking calendar.
                * Stop bookings in advance added.
                * Translation for dynamic items display correctly, both in booking calendar and WordPress admin.
                * Translation works with special characters, both in booking calendar and WordPress admin.
                * UAE Dirham currency added.
                * User capabilities repaired, in WordPress admin.
                * Verification if booking calendar has been attached to WooCommerce product has been added.
                * WooCommerce integration has been changed. This should fix all incompatibility problems that were in previous version.
                * WooCommerce redirect to cart page after a booking/reservation is added to cart, bug repaired.

        1.9.5 (2014-03-01)

                * "wp_mail()" function replaced with "mail()".
                * Booking calendar display even it is used twice on same page.
                * Booking order is added to WooCommerce cart even if form is removed from product page.
                * Booking/reservation details appear on WooCommerce notifications email.
                * Booking/reservation details appear on WooCommerce order.
                * Booking/reservation save bug repaired.
                * Installation on XAMP server repaired.
                * WooCommerce date format repaired.

        1.9 (2013-12-16)
 
                * bbPress incompatibility, bug repaired.
                * Booking calendars not loading, bug repaired.
                * Bookings/reservations appear in custom post type.
                * Bookings/reservations currency display bug repaired.
                * Config file added.
                * CSS bugs repaired.
                * Delete plugin data/database, bug repaired.
                * Delete booking/reservation added.
                * Front end translation not showing, bug repaired.
                * Hooks added.
                * Installation algorithms have been optimized.
                * Month not displaying in booking notification emails bug repaired.
                * Navigation after data is saved in back end repaired.
                * Reservations calendar is generated correctly when filters are modified.
                * Save translation bug repaired.
                * Set default database values before installation.
                * Set default language for back end and/or front end before installation.
                * Set default users permissions before installation.
                * Submit button ("Add to cart" / "Book") is hidden when you submit a booking or you add a reservation to cart.
                * Translation display bug repaired when using characters like ' or ", both in booking calendar and WordPress admin.
                * Translation edit has been optimized.
                * When a booking calendar is deleted the reservations area is removed.
                * WooCommerce support added.
   
        1.8 (2013-11-01)
                
                * Add bookins/reservations in WordPress admin.
                * Approving/canceling a reservation modifies the booking calendar data.
                * Back end CSS bugs repaired.
                * Bookings/reservations logic has been completly modified (search added, filters added, calendar & list view added).
                * Custom post types bugs repaired.
                * Edit unavailable days, bug repaired.
                * Front end booking calendar CSS bugs repaired.
                * Instant/waiting approval display, bug repaired.
                * JavaScript in admin posts repaired.
                * Localhost bugs repaired.
                * Plugin paths updated.
                * Prices, deposits, discounts can have float values.
                * Select days from different months on front end booking calendar, bug repaired.
                * Translation system has been updated.
                * User management updated.
                * Windows server mySQL text fields bug repaired.

        1.7 (2013-07-31)

                * Add booking calendars in widgets.
                * Approve booking/reservation bug repaired.
                * Back end style changes.
                * Calendar ID is removed from clients booking notification emails.
                * CSS bug fixes, in booking calendar.
                * Custom post type added.
                * Date select is repaired when minimum amount of days is set.
                * Datepicker bug fix, when you can select only one day, in booking calendar.
                * Drop down fields display correct selected option in booking notifications.
                * Hours info is displayed on day hover, in booking calendar.
                * Major changes in booking hours logic and display.
                * Newly created booking forms display correct after PayPal Payment.
                * PayPal booking notification email content bug repaired.
                * Send email using normal function if SMTP is incorrect.
                * Tables not created on Windows OS bug repaired.
                * Text on Settings page, in WordPress admin, has been changed.
                * Translation for check fields added.
                * User role is updated when is changed in WordPress admin.
                * When hours are enabled, days details can be set manually or set depending on hours details on that current day.
                * WordPress update error repaired. 

        1.6 (2013-06-15)

                * Admin language is different for each user, in WordPress back end.
                * Compatibility fixes, in WordPress back end.
                * Custom booking forms tweaks.
                * Database update.
                * Datepicker & Google translate incompatibility, bug repaired in booking calendar.
                * Display calendar id & name in notifications emails.
                * Display hours interval from current hour to next one.
                * Possibility to hide number of items select field has been added, in booking calendar.
                * You can set booking requests to by approved instantly, or not.
                * You have the possibility to calculate the total price using the last hour selected value, or not.

        1.5 (2013-06-08)

                * CSS incompatibility fixes, in front end booking calendar.
                * Custom booking forms added.
                * Datepicker z-index bug repaired, in front end booking calendar.
                * Email header is custom.
                * Group day date is displayed correctly after select, in front end booking calendar.
                * Users permissons translation repaired.
 
        1.4 (2013-06-03)

                * ACAO buster added.
                * Admin change language bug repaired.
                * Administrators can create booking calendars for users.
                * Booking calendar loading time is improved.
                * Booking calendar resize on hidden elements, bug repaired.
                * Booking notifications are sent using "wp_mail()".
                * Database is deleted when you delete the booking system plugin.
                * Display only an information calendar in front end.
                * Indonesia Rupiah currency bug repaired.
                * PayPal credit card payment bug repaired.
                * PayPal session bug repaired.
                * Select first day of the week, in booking calendar.
                * Slow admin bug repaired.
                * Small admin changes.
                * Touch devices freeze bug repaired.
                * Translation fixes, both in front end booking calendar and back end.
                * Update booking notification added.
                * User permissions updated, in booking system back end.

        1.3 (2012-12-13)

                * Booking notifications message and language bugs have been repaired.
                * Correct hours format is displayed, in front end booking calendar.
                * Deposit feature has been added, for booking requests.
                * Discounts by number of days booked have been added.
                * Front end booking calendar is responsive.
                * Touch devices navigation has been enabled.
                * You can translate the sidebar datepicker.
                * You can use PayPal credit card payment, for booking requests.
	
	1.2 (2012-11-01)
 
                * AM/PM hour format added, in front end booking calendar and WordPress admin.
                * Booking/reservation cancel added.
                * Hours data save bug repaired.
                * Language files added (but not translated), for front end booking calendar and back end WordPress admin.
                * Morning check out added, in booking calendar.
                * Past hours are removed from current day, in booking calendar.
                * Rejected booking/reservation notification email repaired.  
                * Shortcode generator doesn't appear if you are not allowed to create booking calendars or you did nt create any booking calendars.
                * SMTP SSL fix, when sending booking notifications.
                * User permissions bug repaired, in WordPress admin.
                * You can select minimum and/or maximum amount of days that can be booked.
                * You can set default hours values by day(s), in WordPress admin.

	1.1 (2012-09-05)
	
                * "ereg()" function replaced with "preg_match()".
                * Administrators can view and edit users booking calendars.
                * Back end & front end CSS incompatibility fixes.
                * Clean script to remove past days info to clear database from unnecessary data.
		* Database structure has been changed (now is much faster to save/load data & works on server with few resources).
                * Delete booking calendar bug repaired.
                * Display correct month in future years, bug repaired.
                * Emails template system added, for booking notifications.
                * PayPal bugs repaired.
                * Reservation ID is displayed in notifications emails.
                * Terms & Conditions checkbox and link added.
                * You can now add the booking calendar sidebar in a widget area.
                * You can set if individual users can create or not booking calendars.
                * You can use SMTP to send booking notifications emails.
	
	1.0 (2012-07-15)
	
		* Initial release of Booking System PRO (WordPress Plugin).
		
Installation: Upload the folder dopbsp from the zip file to "wp-content/plugins/" and activate the plugin in your admin panel or upload dopbsp.zip in the "Add new" section.
*/

    // ini_set('error_reporting', version_compare(PHP_VERSION,5,'>=') && version_compare(PHP_VERSION,6,'<') ? E_ALL^E_STRICT^E_NOTICE^E_WARNING:E_ALL^E_NOTICE^E_WARNING);

    /*
     * Constants
     */
    define('DOPBSP_DEVELOPMENT_MODE', isset($_SERVER['SERVER_NAME']) ? ($_SERVER['SERVER_NAME'] == 'dopstudios.net' ? false:false):false);
    define('DOPBSP_REPAIR_DATABASE_TEXT', isset($_POST['dopbsp_repair_database_text']) ? true:false);
    
    DOPBSPErrorsHandler::start();
    
    try{
        /*
         * Configuration file
         */
        include_once 'dopbsp-config.php';

        /*
         * Libraries
         */
        include_once 'libraries/php/class-wc-plugin-update.php';
        include_once 'libraries/php/dop-prototypes.php';
        include_once 'libraries/php/class.iCalReader.php';
        include_once ABSPATH.WPINC.'/class-phpmailer.php';
        include_once ABSPATH.WPINC.'/class-smtp.php';

        /*
         * Views
         */
        include_once 'views/views.php';

        include_once 'views/views-backend.php';
        include_once 'views/views-frontend.php';

        include_once 'views/addons/views-backend-addons.php';
        include_once 'views/addons/views-backend-addons-filters.php';
        include_once 'views/addons/views-backend-addons-list.php';

        include_once 'views/amenities/views-backend-amenities.php';

        include_once 'views/calendars/views-backend-calendars.php';

        include_once 'views/coupons/views-backend-coupons.php';
        include_once 'views/coupons/views-backend-coupon.php';

        include_once 'views/dashboard/views-backend-dashboard.php';
        include_once 'views/dashboard/views-backend-dashboard-api.php';
        include_once 'views/dashboard/views-backend-dashboard-server.php';
        include_once 'views/dashboard/views-backend-dashboard-start.php';

        include_once 'views/discounts/views-backend-discounts.php';
        include_once 'views/discounts/views-backend-discount.php';
        include_once 'views/discounts/views-backend-discount-items.php';
        include_once 'views/discounts/views-backend-discount-item.php';
        include_once 'views/discounts/views-backend-discount-item-rule.php';

        include_once 'views/emails/views-backend-emails.php';
        include_once 'views/emails/views-backend-email.php';

        include_once 'views/extras/views-backend-extras.php';
        include_once 'views/extras/views-backend-extra.php';
        include_once 'views/extras/views-backend-extra-groups.php';
        include_once 'views/extras/views-backend-extra-group.php';
        include_once 'views/extras/views-backend-extra-group-item.php';

        include_once 'views/fees/views-backend-fees.php';
        include_once 'views/fees/views-backend-fee.php';

        include_once 'views/forms/views-backend-forms.php';
        include_once 'views/forms/views-backend-form.php';
        include_once 'views/forms/views-backend-form-fields.php';
        include_once 'views/forms/views-backend-form-field.php';
        include_once 'views/forms/views-backend-form-field-select-option.php';
        
        include_once 'views/languages/views-backend-languages.php';

        include_once 'views/locations/views-backend-locations.php';
        include_once 'views/locations/views-backend-location.php';

        include_once 'views/models/views-backend-models.php';
        include_once 'views/models/views-backend-model.php';

        include_once 'views/reservations/views-backend-reservations.php';
        include_once 'views/reservations/views-backend-reservations-list.php';
        include_once 'views/reservations/views-backend-reservation.php';
        include_once 'views/reservations/views-backend-reservation-address.php';
        include_once 'views/reservations/views-backend-reservation-coupon.php';
        include_once 'views/reservations/views-backend-reservation-details.php';
        include_once 'views/reservations/views-backend-reservation-discount.php';
        include_once 'views/reservations/views-backend-reservation-extras.php';
        include_once 'views/reservations/views-backend-reservation-fees.php';
        include_once 'views/reservations/views-backend-reservation-form.php';

        include_once 'views/reviews/views-backend-reviews.php';

        include_once 'views/rules/views-backend-rules.php';
        include_once 'views/rules/views-backend-rule.php';

        include_once 'views/search/views-backend-searches.php';
        include_once 'views/search/views-backend-search.php';
        include_once 'views/search/views-frontend-search.php';
        include_once 'views/search/views-frontend-search-results.php';
        include_once 'views/search/views-frontend-search-results-grid.php';
        include_once 'views/search/views-frontend-search-results-list.php';
        include_once 'views/search/views-frontend-search-sidebar.php';
        include_once 'views/search/views-frontend-search-sort.php';
        include_once 'views/search/views-frontend-search-views.php';

        include_once 'views/settings/views-backend-settings.php';
        include_once 'views/settings/views-backend-settings-calendar.php';
        include_once 'views/settings/views-backend-settings-general.php';
        include_once 'views/settings/views-backend-settings-licences.php';
        include_once 'views/settings/views-backend-settings-notifications.php';
        include_once 'views/settings/views-backend-settings-payment-gateways.php';
        include_once 'views/settings/views-backend-settings-search.php';
        include_once 'views/settings/views-backend-settings-users.php';
        
        include_once 'views/smses/views-backend-smses.php';
        include_once 'views/smses/views-backend-sms.php';
        
        include_once 'views/templates/views-backend-templates.php';

        include_once 'views/themes/views-backend-themes.php';
        include_once 'views/themes/views-backend-themes-filters.php';
        include_once 'views/themes/views-backend-themes-list.php';

        include_once 'views/tools/views-backend-tools.php';
        include_once 'views/tools/views-backend-tools-repair-calendars-settings.php';
        include_once 'views/tools/views-backend-tools-repair-search-settings.php';

        include_once 'views/translation/views-backend-translation.php';

        /*
         * Classes
         */
        include_once 'includes/class-backend.php';
        include_once 'includes/class-frontend.php';

        include_once 'includes/addons/class-backend-addons.php';

        include_once 'includes/amenities/class-backend-amenities.php';
        
        include_once 'includes/api/class-backend-api-key.php';
        include_once 'includes/api/class-frontend-api.php';

        include_once 'includes/calendars/class-backend-calendars.php';
        include_once 'includes/calendars/class-backend-calendar.php';
        include_once 'includes/calendars/class-backend-calendar-availability.php';
        include_once 'includes/calendars/class-backend-calendar-schedule.php';
        include_once 'includes/calendars/class-frontend-calendar.php';
        include_once 'includes/calendars/class-frontend-calendar-sidebar.php';
        
        include_once 'includes/class-countries.php';

        include_once 'includes/coupons/class-backend-coupons.php';
        include_once 'includes/coupons/class-backend-coupon.php';
        include_once 'includes/coupons/class-frontend-coupons.php';

        include_once 'includes/class-currencies.php';

        include_once 'includes/custom-posts/class-custom-posts.php';
        include_once 'includes/custom-posts/class-custom-posts-loop.php';
        include_once 'includes/custom-posts/class-custom-posts-meta.php';

        include_once 'includes/dashboard/class-backend-dashboard.php';

        include_once 'includes/class-database.php';

        include_once 'includes/discounts/class-backend-discounts.php';
        include_once 'includes/discounts/class-backend-discount.php';
        include_once 'includes/discounts/class-backend-discount-items.php';
        include_once 'includes/discounts/class-backend-discount-item.php';
        include_once 'includes/discounts/class-backend-discount-item-rules.php';
        include_once 'includes/discounts/class-backend-discount-item-rule.php';
        include_once 'includes/discounts/class-frontend-discounts.php';

        include_once 'includes/emails/class-backend-emails.php';
        include_once 'includes/emails/class-backend-email.php';
        
        include_once 'includes/smses/class-backend-smses.php';
        include_once 'includes/smses/class-backend-sms.php';

        include_once 'includes/extras/class-backend-extras.php';
        include_once 'includes/extras/class-backend-extra.php';
        include_once 'includes/extras/class-backend-extra-groups.php';
        include_once 'includes/extras/class-backend-extra-group.php';
        include_once 'includes/extras/class-backend-extra-group-items.php';
        include_once 'includes/extras/class-backend-extra-group-item.php';
        include_once 'includes/extras/class-frontend-extras.php';

        include_once 'includes/fees/class-backend-fees.php';
        include_once 'includes/fees/class-backend-fee.php';
        include_once 'includes/fees/class-frontend-fees.php';

        include_once 'includes/forms/class-backend-forms.php';
        include_once 'includes/forms/class-backend-form.php';
        include_once 'includes/forms/class-backend-form-fields.php';
        include_once 'includes/forms/class-backend-form-field.php';
        include_once 'includes/forms/class-backend-form-field-select-options.php';
        include_once 'includes/forms/class-backend-form-field-select-option.php';
        include_once 'includes/forms/class-frontend-forms.php';

        include_once 'includes/languages/class-languages.php';
        include_once 'includes/languages/class-backend-languages.php';
        include_once 'includes/languages/class-backend-language.php';

        include_once 'includes/locations/class-backend-locations.php';
        include_once 'includes/locations/class-backend-location.php';
	
        include_once 'includes/models/class-backend-models.php';
        include_once 'includes/models/class-backend-model.php';

        include_once 'includes/class-notifications.php';

        include_once 'includes/order/class-frontend-order.php';

        include_once 'includes/class-payment-gateways.php';

        include_once 'includes/class-price.php';

        include_once 'includes/reservations/class-backend-reservations.php';
        include_once 'includes/reservations/class-backend-reservations-add.php';
        include_once 'includes/reservations/class-backend-reservations-calendar.php';
        include_once 'includes/reservations/class-backend-reservations-list.php';
        include_once 'includes/reservations/class-backend-reservation.php';
        include_once 'includes/reservations/class-backend-reservation-form.php';
        include_once 'includes/reservations/class-backend-reservation-notifications.php';
        include_once 'includes/reservations/class-frontend-reservations.php';

        include_once 'includes/reviews/class-backend-reviews.php';

        include_once 'includes/rules/class-backend-rules.php';
        include_once 'includes/rules/class-backend-rule.php';
        include_once 'includes/rules/class-frontend-rules.php';

        include_once 'includes/search/class-backend-searches.php';
        include_once 'includes/search/class-backend-search.php';
        include_once 'includes/search/class-frontend-search.php';
        include_once 'includes/search/class-frontend-search-results.php';

        include_once 'includes/settings/class-backend-settings.php';
        include_once 'includes/settings/class-backend-settings-calendar.php';
        include_once 'includes/settings/class-backend-settings-general.php';
        include_once 'includes/settings/class-backend-settings-licences.php';
        include_once 'includes/settings/class-backend-settings-notifications.php';
        include_once 'includes/settings/class-backend-settings-payment-gateways.php';
        include_once 'includes/settings/class-backend-settings-search.php';
        include_once 'includes/settings/class-backend-settings-users.php';

        include_once 'includes/shortcodes/class-backend-shortcodes.php';

        include_once 'includes/templates/class-backend-templates.php';

        include_once 'includes/themes/class-backend-themes.php';

        include_once 'includes/tools/class-backend-tools.php';
        include_once 'includes/tools/class-backend-tools-repair-calendars-settings.php';
        include_once 'includes/tools/class-backend-tools-repair-database-text.php';
        include_once 'includes/tools/class-backend-tools-repair-search-settings.php';

        include_once 'includes/translation/class-translation-text-addons.php';
        include_once 'includes/translation/class-translation-text-amenities.php';
        include_once 'includes/translation/class-translation-text-calendars.php';
        include_once 'includes/translation/class-translation-text-cart.php';
        include_once 'includes/translation/class-translation-text-coupons.php';
        include_once 'includes/translation/class-translation-text-custom-posts.php';
        include_once 'includes/translation/class-translation-text-dashboard.php';
        include_once 'includes/translation/class-translation-text-discounts.php';
        include_once 'includes/translation/class-translation-text-emails.php';
        include_once 'includes/translation/class-translation-text-extras.php';
        include_once 'includes/translation/class-translation-text-forms.php';
        include_once 'includes/translation/class-translation-text-fees.php';
        include_once 'includes/translation/class-translation-text-general.php';
        include_once 'includes/translation/class-translation-text-languages.php';
        include_once 'includes/translation/class-translation-text-locations.php';
        include_once 'includes/translation/class-translation-text-models.php';
        include_once 'includes/translation/class-translation-text-order.php';
        include_once 'includes/translation/class-translation-text-reservations.php';
        include_once 'includes/translation/class-translation-text-reviews.php';
        include_once 'includes/translation/class-translation-text-rules.php';
        include_once 'includes/translation/class-translation-text-search.php';
        include_once 'includes/translation/class-translation-text-settings.php';
        include_once 'includes/translation/class-translation-text-smses.php';
        include_once 'includes/translation/class-translation-text-templates.php';
        include_once 'includes/translation/class-translation-text-themes.php';
        include_once 'includes/translation/class-translation-text-tools.php';
        include_once 'includes/translation/class-translation-text-translation.php';
        include_once 'includes/translation/class-translation-text-widgets.php';
        include_once 'includes/translation/class-translation.php';
        include_once 'includes/translation/class-backend-translation.php';
        
        include_once 'includes/class-update.php';

        include_once 'includes/class-widget.php';
        
        /*
         * Addons
         */
        include_once 'addons/paypal/dopbsp-paypal.php';
        include_once 'addons/clickatell/dopbsp-clickatell.php';
	
	/*
	 * Include configuration.
	 */
	include_once 'framework/config/config.php';
	
	/*
	 * Include main class.
	 */
	include_once 'framework/dot.php';
	
        $theme = wp_get_theme();
        $theme->name == 'Beautify' || $theme->parent_theme == 'Beautify' ? include_once 'addons/themes/beautify/dopbsp-themes-beautify.php':'';
        
        in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || DOPBSP_CONFIG_WOOCOMMERCE_ENABLE_CODE ? include_once 'addons/woocommerce/dopbsp-woocommerce.php':'';
    }
    catch(Exception $ex){
        add_action('admin_notices', 'dopbspMissingFiles');
    }
    
    DOPBSPErrorsHandler::finish();
    
    /*
     * Global classes.
     */
    global $DOPBSP;
    global $DOT;
    global $dot_addons;
    
    /*
     * Initialize API.
     */
    if (class_exists('DOT')){
	$DOT = new DOT();
	$DOT->init();
    }
    
    /*
     * Booking System PRO main PHP class.
     */
    if (!class_exists('DOPBSP')){
        class DOPBSP{
            /*
             * Public variables.
             */
            public $classes;
            public $paths;
            public $tables;
            public $vars;
            public $views;
            
            /*
             * Constructor
             */
            function __construct(){
                $this->classes = new stdClass;
                $this->paths = new stdClass;
                $this->tables = new stdClass;
                $this->vars = new stdClass;
                $this->views = new stdClass;
            
                $this->definePaths();
                $this->defineTables();
                
                /*
                 * Initialize plugin classes.
                 */
                $this->initClasses();
                
                if (is_admin() 
                        && !isset($_POST['dopbsp_frontend_ajax_request'])){
                    add_action('admin_menu', array(&$this, 'initBackEnd'));
                    $this->initBackEndAJAX();
                }
                else{
                    $this->initFrontEndAJAX();
                }
                
                /*
                 * Initialize plugin's widget.
                 */
                if (class_exists('DOPBSPWidget')){
                    function dopbsp_register_widgets(){
                        register_widget("DOPBSPWidget");
                    }
                    add_action('widgets_init', 'dopbsp_register_widgets');
                }
            }
            
            /*
             * Defines plugin's paths constants.
             */
            function definePaths(){
                /*
                 * Plugin URL.
                 */
                $this->paths->url =  plugin_dir_url(__FILE__);

                /*
                 * Absolute path.
                 */
                $this->paths->abs = str_replace('\\', '/', plugin_dir_path(__FILE__));
            }
            
            /*
             * Defines plugin's tables constants.
             */
            function defineTables(){
                global $wpdb;
                
                /*
                 * API keys table.
                 */
                $this->tables->api_keys = $wpdb->prefix.'dopbsp_api_keys';
		
		/*
		 * Availability tables.
		 */
                $this->tables->availability = $wpdb->prefix.'dopbsp_availability';
                $this->tables->availability_no = $wpdb->prefix.'dopbsp_availability_no';
                $this->tables->availability_price = $wpdb->prefix.'dopbsp_availability_price';
                
                /*
                 * Businesses table.
                 */
                $this->tables->businesses = $wpdb->prefix.'dopbsp_businesses';
                
                /*
                 * Calendars table.
                 */
                $this->tables->calendars = $wpdb->prefix.'dopbsp_calendars';

                /*
                 * Counpons tables.
                 */
                $this->tables->coupons = $wpdb->prefix.'dopbsp_coupons';

                /*
                 * Days table.
                 */
                $this->tables->days = $wpdb->prefix.'dopbsp_days';

                /*
                 * Discounts tables.
                 */
                $this->tables->discounts = $wpdb->prefix.'dopbsp_discounts';
                $this->tables->discounts_items = $wpdb->prefix.'dopbsp_discounts_items';
                $this->tables->discounts_items_rules = $wpdb->prefix.'dopbsp_discounts_items_rules';

                /*
                 * Emails tables.
                 */
                $this->tables->emails = $wpdb->prefix.'dopbsp_emails';
                $this->tables->emails_translation = $wpdb->prefix.'dopbsp_emails_translation';
                
                /*
                 * Extras tables.
                 */
                $this->tables->extras = $wpdb->prefix.'dopbsp_extras';
                $this->tables->extras_groups = $wpdb->prefix.'dopbsp_extras_groups';
                $this->tables->extras_groups_items = $wpdb->prefix.'dopbsp_extras_groups_items';

                /*
                 * Fees tables.
                 */
                $this->tables->fees = $wpdb->prefix.'dopbsp_fees';
                
                /*
                 * Forms tables.
                 */
                $this->tables->forms = $wpdb->prefix.'dopbsp_forms';
                $this->tables->forms_fields = $wpdb->prefix.'dopbsp_forms_fields';
                $this->tables->forms_fields_options = $wpdb->prefix.'dopbsp_forms_select_options';
                
                /*
                 * Languages tables.
                 */
                $this->tables->languages = $wpdb->prefix.'dopbsp_languages';
                
                /*
                 * Locations tables.
                 */
                $this->tables->locations = $wpdb->prefix.'dopbsp_locations';
                
                /*
                 * Models table.
                 */
                $this->tables->models = $wpdb->prefix.'dopbsp_models';

                /*
                 * Reservations table.
                 */
                $this->tables->reservations = $wpdb->prefix.'dopbsp_reservations';

                /*
                 * Rules tables.
                 */
                $this->tables->rules = $wpdb->prefix.'dopbsp_rules';

                /*
                 * Search table.
                 */
                $this->tables->searches = $wpdb->prefix.'dopbsp_searches';

                /*
                 * Settings table.
                 */
                $this->tables->settings = $wpdb->prefix.'dopbsp_settings';
                $this->tables->settings_calendar = $wpdb->prefix.'dopbsp_settings_calendar';
                $this->tables->settings_notifications = $wpdb->prefix.'dopbsp_settings_notifications';
                $this->tables->settings_payment = $wpdb->prefix.'dopbsp_settings_payment';
                $this->tables->settings_search = $wpdb->prefix.'dopbsp_settings_search';
                
                /*
                 * SMSes tables.
                 */
                $this->tables->smses = $wpdb->prefix.'dopbsp_smses';
                $this->tables->smses_translation = $wpdb->prefix.'dopbsp_smses_translation';
                /*
                 * Translation tables.
                 */
                $this->tables->translation = $wpdb->prefix.'dopbsp_translation';
            }
            
            /*
             * Initialize PHP classes.
             */
            function initClasses(){
                /*
                 * Initialize views class. This class is the 1st initialized.
                 */
                $this->views = class_exists('DOPBSPViews') ? new DOPBSPViews():'Class does not exist!';
                
                /*
                 *  Initialize DOP prototypes class. This class is the 2nd initialized.
                 */
                $this->classes->prototypes = class_exists('DOPPrototypes') ? new DOPPrototypes():'Class does not exist!';
                
                /*
                 * Initialize database class. This class is the 3rd initialized.
                 */
                $this->classes->database = class_exists('DOPBSPDatabase') ? new DOPBSPDatabase():'Class does not exist!';
   
                /*
                 * Initialize languages class. This class is the 4th initialized.
                 */
                $this->classes->languages = class_exists('DOPBSPLanguages') ? new DOPBSPLanguages():'Class does not exist!';
    
                /*
                 * Initialize countries class. This class is the 5th initialized.
                 */
                $this->classes->countries = class_exists('DOPBSPCountries') ? new DOPBSPCountries():'Class does not exist!';
    
                /*
                 * Initialize translation classes. This class is the 6th initialized in alphabetical order, but main class will last.
                 */
                class_exists('DOPBSPTranslationTextAddons') ? new DOPBSPTranslationTextAddons():'Class does not exist!';
                class_exists('DOPBSPTranslationTextAmenities') ? new DOPBSPTranslationTextAmenities():'Class does not exist!';
                class_exists('DOPBSPTranslationTextCalendars') ? new DOPBSPTranslationTextCalendars():'Class does not exist!';
                class_exists('DOPBSPTranslationTextCart') ? new DOPBSPTranslationTextCart():'Class does not exist!';
                class_exists('DOPBSPTranslationTextCoupons') ? new DOPBSPTranslationTextCoupons():'Class does not exist!';
                class_exists('DOPBSPTranslationTextCustomPosts') ? new DOPBSPTranslationTextCustomPosts():'Class does not exist!';
                class_exists('DOPBSPTranslationTextDashboard') ? new DOPBSPTranslationTextDashboard():'Class does not exist!';
                class_exists('DOPBSPTranslationTextDiscounts') ? new DOPBSPTranslationTextDiscounts():'Class does not exist!';
                class_exists('DOPBSPTranslationTextEmails') ? new DOPBSPTranslationTextEmails():'Class does not exist!';
                class_exists('DOPBSPTranslationTextExtras') ? new DOPBSPTranslationTextExtras():'Class does not exist!';
                class_exists('DOPBSPTranslationTextFees') ? new DOPBSPTranslationTextFees():'Class does not exist!';
                class_exists('DOPBSPTranslationTextForms') ? new DOPBSPTranslationTextForms():'Class does not exist!';
                class_exists('DOPBSPTranslationTextGeneral') ? new DOPBSPTranslationTextGeneral():'Class does not exist!';
                class_exists('DOPBSPTranslationTextLanguages') ? new DOPBSPTranslationTextLanguages():'Class does not exist!';
                class_exists('DOPBSPTranslationTextLocations') ? new DOPBSPTranslationTextLocations():'Class does not exist!';
                class_exists('DOPBSPTranslationTextModels') ? new DOPBSPTranslationTextModels():'Class does not exist!';
                class_exists('DOPBSPTranslationTextOrder') ? new DOPBSPTranslationTextOrder():'Class does not exist!';
                class_exists('DOPBSPTranslationTextReservations') ? new DOPBSPTranslationTextReservations():'Class does not exist!';
                class_exists('DOPBSPTranslationTextReviews') ? new DOPBSPTranslationTextReviews():'Class does not exist!';
                class_exists('DOPBSPTranslationTextRules') ? new DOPBSPTranslationTextRules():'Class does not exist!';
                class_exists('DOPBSPTranslationTextSearch') ? new DOPBSPTranslationTextSearch():'Class does not exist!';
                class_exists('DOPBSPTranslationTextSettings') ? new DOPBSPTranslationTextSettings():'Class does not exist!';
                class_exists('DOPBSPTranslationTextSmses') ? new DOPBSPTranslationTextSmses():'Class does not exist!';
                class_exists('DOPBSPTranslationTextTemplates') ? new DOPBSPTranslationTextTemplates():'Class does not exist!';
                class_exists('DOPBSPTranslationTextThemes') ? new DOPBSPTranslationTextThemes():'Class does not exist!';
                class_exists('DOPBSPTranslationTextTools') ? new DOPBSPTranslationTextTools():'Class does not exist!';
                class_exists('DOPBSPTranslationTextTranslation') ? new DOPBSPTranslationTextTranslation():'Class does not exist!';
                class_exists('DOPBSPTranslationTextWidgets') ? new DOPBSPTranslationTextWidgets():'Class does not exist!';
     
                $this->classes->translation = class_exists('DOPBSPTranslation') ? new DOPBSPTranslation():'Class does not exist!';
    
                /*
                 * Initialize currencies class. This class is the 7th initialized.
                 */
                $this->classes->currencies = class_exists('DOPBSPCurrencies') ? new DOPBSPCurrencies():'Class does not exist!';

                /*
                 * Initialize notifications class. This class is the 8th initialized.
                 */
                $this->classes->notifications = class_exists('DOPBSPNotifications') ? new DOPBSPNotifications():'Class does not exist!';
                
                /*
                 * Initialize back end class. This class is the 9th initialized.
                 */
                $this->classes->backend = class_exists('DOPBSPBackEnd') ? new DOPBSPBackEnd():'Class does not exist!';
                
                /*
                 * Initialize front end class. This class is the 10th initialized.
                 */
                $this->classes->frontend = class_exists('DOPBSPFrontEnd') ? new DOPBSPFrontEnd():'Class does not exist!';
                
                /*
                 * ************************************************************* The rest of the classes are initialized in alphabetical order.
                 */
                /*
                 * Initialize amenities classes.
                 */
                $this->classes->backend_amenities = class_exists('DOPBSPBackEndAmenities') ? new DOPBSPBackEndAmenities():'Class does not exist!';
                
                /*
                 * Initialize addons classes.
                 */
                $this->classes->backend_addons = class_exists('DOPBSPBackEndAddons') ? new DOPBSPBackEndAddons():'Class does not exist!';
                
                /*
                 * Initialize API classes.
                 */
                $this->classes->backend_api_key = class_exists('DOPBSPBackEndAPIKey') ? new DOPBSPBackEndAPIKey():'Class does not exist!';
                $this->classes->frontend_api_key = class_exists('DOPBSPFrontEndAPI') ? new DOPBSPFrontEndAPI():'Class does not exist!';
		
                /*
                 * Initialize businesses classes.
                 */
                $this->classes->backend_businesses = class_exists('DOPBSPBackEndBusinesses') ? new DOPBSPBackEndBusinesses():'Class does not exist!';
                $this->classes->backend_business = class_exists('DOPBSPBackEndBusiness') ? new DOPBSPBackEndBusiness():'Class does not exist!';
                
                /*
                 * Initialize calendars classes.
                 */
                $this->classes->backend_calendars = class_exists('DOPBSPBackEndCalendars') ? new DOPBSPBackEndCalendars():'Class does not exist!';
                $this->classes->backend_calendar = class_exists('DOPBSPBackEndCalendar') ? new DOPBSPBackEndCalendar():'Class does not exist!';
                $this->classes->backend_calendar_availability = class_exists('DOPBSPBackEndCalendarAvailability') ? new DOPBSPBackEndCalendarAvailability():'Class does not exist!';
                $this->classes->backend_calendar_schedule = class_exists('DOPBSPBackEndCalendarSchedule') ? new DOPBSPBackEndCalendarSchedule():'Class does not exist!';
                $this->classes->frontend_calendar = class_exists('DOPBSPFrontEndCalendar') ? new DOPBSPFrontEndCalendar():'Class does not exist!';
                $this->classes->frontend_calendar_sidebar = class_exists('DOPBSPFrontEndCalendarSidebar') ? new DOPBSPFrontEndCalendarSidebar():'Class does not exist!';

                /*
                 * Initialize coupons classes.
                 */
                $this->classes->backend_coupons = class_exists('DOPBSPBackEndCoupons') ? new DOPBSPBackEndCoupons():'Class does not exist!';
                $this->classes->backend_coupon = class_exists('DOPBSPBackEndCoupon') ? new DOPBSPBackEndCoupon():'Class does not exist!';
                $this->classes->frontend_coupons = class_exists('DOPBSPFrontEndCoupons') ? new DOPBSPFrontEndCoupons():'Class does not exist!';

                /*
                 * Initialize custom posts classes.
                 */
                $this->classes->custom_posts = class_exists('DOPBSPCustomPosts') ? new DOPBSPCustomPosts():'Class does not exist!';
                $this->classes->custom_posts_loop = class_exists('DOPBSPCustomPostsLoop') ? new DOPBSPCustomPostsLoop():'Class does not exist!';
                $this->classes->custom_posts_meta = class_exists('DOPBSPCustomPostsMeta') ? new DOPBSPCustomPostsMeta():'Class does not exist!';

                /*
                 * Initialize dashboard classes.
                 */
                $this->classes->backend_dashboard = class_exists('DOPBSPBackEndDashboard') ? new DOPBSPBackEndDashboard():'Class does not exist!';

                /*
                 * Initialize discounts classes.
                 */
                $this->classes->backend_discounts = class_exists('DOPBSPBackEndDiscounts') ? new DOPBSPBackEndDiscounts():'Class does not exist!';
                $this->classes->backend_discount = class_exists('DOPBSPBackEndDiscount') ? new DOPBSPBackEndDiscount():'Class does not exist!';
                $this->classes->backend_discount_items = class_exists('DOPBSPBackEndDiscountItems') ? new DOPBSPBackEndDiscountItems():'Class does not exist!';
                $this->classes->backend_discount_item = class_exists('DOPBSPBackEndDiscountItem') ? new DOPBSPBackEndDiscountItem():'Class does not exist!';
                $this->classes->backend_discount_item_rules = class_exists('DOPBSPBackEndDiscountItemRules') ? new DOPBSPBackEndDiscountItemRules():'Class does not exist!';
                $this->classes->backend_discount_item_rule = class_exists('DOPBSPBackEndDiscountItemRule') ? new DOPBSPBackEndDiscountItemRule():'Class does not exist!';
                $this->classes->frontend_discounts = class_exists('DOPBSPFrontEndDiscounts') ? new DOPBSPFrontEndDiscounts():'Class does not exist!';

                /*
                 * Initialize emails classes.
                 */
                $this->classes->backend_emails = class_exists('DOPBSPBackEndEmails') ? new DOPBSPBackEndEmails():'Class does not exist!';
                $this->classes->backend_email = class_exists('DOPBSPBackEndEmail') ? new DOPBSPBackEndEmail():'Class does not exist!';

                /*
                 * Initialize extras classes.
                 */
                $this->classes->backend_extras = class_exists('DOPBSPBackEndExtras') ? new DOPBSPBackEndExtras():'Class does not exist!';
                $this->classes->backend_extra = class_exists('DOPBSPBackEndExtra') ? new DOPBSPBackEndExtra():'Class does not exist!';
                $this->classes->backend_extra_groups = class_exists('DOPBSPBackEndExtraGroups') ? new DOPBSPBackEndExtraGroups():'Class does not exist!';
                $this->classes->backend_extra_group = class_exists('DOPBSPBackEndExtraGroup') ? new DOPBSPBackEndExtraGroup():'Class does not exist!';
                $this->classes->backend_extra_group_items = class_exists('DOPBSPBackEndExtraGroupItems') ? new DOPBSPBackEndExtraGroupItems():'Class does not exist!';
                $this->classes->backend_extra_group_item = class_exists('DOPBSPBackEndExtraGroupItem') ? new DOPBSPBackEndExtraGroupItem():'Class does not exist!';
                $this->classes->frontend_extras = class_exists('DOPBSPFrontEndExtras') ? new DOPBSPFrontEndExtras():'Class does not exist!';

                /*
                 * Initialize fees classes.
                 */
                $this->classes->backend_fees = class_exists('DOPBSPBackEndFees') ? new DOPBSPBackEndFees():'Class does not exist!';
                $this->classes->backend_fee = class_exists('DOPBSPBackEndFee') ? new DOPBSPBackEndFee():'Class does not exist!';
                $this->classes->frontend_fees = class_exists('DOPBSPFrontEndFees') ? new DOPBSPFrontEndFees():'Class does not exist!';

                /*
                 * Initialize forms classes.
                 */
                $this->classes->backend_forms = class_exists('DOPBSPBackEndForms') ? new DOPBSPBackEndForms():'Class does not exist!';
                $this->classes->backend_form = class_exists('DOPBSPBackEndForm') ? new DOPBSPBackEndForm():'Class does not exist!';
                $this->classes->backend_form_fields = class_exists('DOPBSPBackEndFormFields') ? new DOPBSPBackEndFormFields():'Class does not exist!';
                $this->classes->backend_form_field = class_exists('DOPBSPBackEndFormField') ? new DOPBSPBackEndFormField():'Class does not exist!';
                $this->classes->backend_form_field_select_options = class_exists('DOPBSPBackEndFormFieldSelectOptions') ? new DOPBSPBackEndFormFieldSelectOptions():'Class does not exist!';
                $this->classes->backend_form_field_select_option = class_exists('DOPBSPBackEndFormFieldSelectOption') ? new DOPBSPBackEndFormFieldSelectOption():'Class does not exist!';
                $this->classes->frontend_forms = class_exists('DOPBSPFrontEndForms') ? new DOPBSPFrontEndForms():'Class does not exist!';

                /*
                 * Initialize languages classes, except main class.
                 */
                $this->classes->backend_languages = class_exists('DOPBSPBackEndLanguages') ? new DOPBSPBackEndLanguages():'Class does not exist!';
                $this->classes->backend_language = class_exists('DOPBSPBackEndLanguage') ? new DOPBSPBackEndLanguage():'Class does not exist!';

                /*
                 * Initialize locations classes.
                 */
                $this->classes->backend_locations = class_exists('DOPBSPBackEndLocations') ? new DOPBSPBackEndLocations():'Class does not exist!';
                $this->classes->backend_location = class_exists('DOPBSPBackEndLocation') ? new DOPBSPBackEndLocation():'Class does not exist!';

                /*
                 * Initialize models classes.
                 */
                $this->classes->backend_models = class_exists('DOPBSPBackEndModels') ? new DOPBSPBackEndModels():'Class does not exist!';
                $this->classes->backend_model = class_exists('DOPBSPBackEndModel') ? new DOPBSPBackEndModel():'Class does not exist!';
		
                /*
                 * Initialize order classes.
                 */
                $this->classes->frontend_order = class_exists('DOPBSPFrontEndOrder') ? new DOPBSPFrontEndOrder():'Class does not exist!';
                
                /*
                 * Initialize payment gateways.
                 */
                $this->classes->payment_gateways = class_exists('DOPBSPPaymentGateways') ? new DOPBSPPaymentGateways():'Class does not exist!';

                /*
                 * Initialize price classes.
                 */
                $this->classes->price = class_exists('DOPBSPPrice') ? new DOPBSPPrice():'Class does not exist!';

                /*
                 * Initialize reservations classes.
                 */
                $this->classes->backend_reservations = class_exists('DOPBSPBackEndReservations') ? new DOPBSPBackEndReservations():'Class does not exist!';
                $this->classes->backend_reservations_add = class_exists('DOPBSPBackEndReservationsAdd') ? new DOPBSPBackEndReservationsAdd():'Class does not exist!';
                $this->classes->backend_reservations_calendar = class_exists('DOPBSPBackEndReservationsCalendar') ? new DOPBSPBackEndReservationsCalendar():'Class does not exist!';
                $this->classes->backend_reservations_list = class_exists('DOPBSPBackEndReservationsList') ? new DOPBSPBackEndReservationsList():'Class does not exist!';
                $this->classes->backend_reservation = class_exists('DOPBSPBackEndReservation') ? new DOPBSPBackEndReservation():'Class does not exist!';
                $this->classes->backend_reservation_form = class_exists('DOPBSPBackEndReservationForm') ? new DOPBSPBackEndReservationForm():'Class does not exist!';
                $this->classes->backend_reservation_notifications = class_exists('DOPBSPBackEndReservationNotifications') ? new DOPBSPBackEndReservationNotifications():'Class does not exist!';
                $this->classes->frontend_reservations = class_exists('DOPBSPFrontEndReservations') ? new DOPBSPFrontEndReservations():'Class does not exist!';
                
                /*
                 * Initialize reviews classes.
                 */
                $this->classes->backend_reviews = class_exists('DOPBSPBackEndReviews') ? new DOPBSPBackEndReviews():'Class does not exist!';

                /*
                 * Initialize rules classes.
                 */
                $this->classes->backend_rules = class_exists('DOPBSPBackEndRules') ? new DOPBSPBackEndRules():'Class does not exist!';
                $this->classes->backend_rule = class_exists('DOPBSPBackEndRule') ? new DOPBSPBackEndRule():'Class does not exist!';
                $this->classes->frontend_rules = class_exists('DOPBSPFrontEndRules') ? new DOPBSPFrontEndRules():'Class does not exist!';

                /*
                 * Initialize search classes.
                 */
                $this->classes->backend_searches = class_exists('DOPBSPBackEndSearches') ? new DOPBSPBackEndSearches():'Class does not exist!';
                $this->classes->backend_search = class_exists('DOPBSPBackEndSearch') ? new DOPBSPBackEndSearch():'Class does not exist!';
                $this->classes->frontend_search = class_exists('DOPBSPFrontEndSearch') ? new DOPBSPFrontEndSearch():'Class does not exist!';
                $this->classes->frontend_search_results = class_exists('DOPBSPFrontEndSearchResults') ? new DOPBSPFrontEndSearchResults():'Class does not exist!';

                /*
                 * Initialize settings classes.
                 */
                $this->classes->backend_settings = class_exists('DOPBSPBackEndSettings') ? new DOPBSPBackEndSettings():'Class does not exist!';
                $this->classes->backend_settings_calendar = class_exists('DOPBSPBackEndSettingsCalendar') ? new DOPBSPBackEndSettingsCalendar():'Class does not exist!';
                $this->classes->backend_settings_general = class_exists('DOPBSPBackEndSettingsGeneral') ? new DOPBSPBackEndSettingsGeneral():'Class does not exist!';
                $this->classes->backend_settings_licences = class_exists('DOPBSPBackEndSettingsLicences') ? new DOPBSPBackEndSettingsLicences():'Class does not exist!';
                $this->classes->backend_settings_notifications = class_exists('DOPBSPBackEndSettingsNotifications') ? new DOPBSPBackEndSettingsNotifications():'Class does not exist!';
                $this->classes->backend_settings_payment_gateways = class_exists('DOPBSPBackEndSettingsPaymentGateways') ? new DOPBSPBackEndSettingsPaymentGateways():'Class does not exist!';
                $this->classes->backend_settings_search = class_exists('DOPBSPBackEndSettingsSearch') ? new DOPBSPBackEndSettingsSearch():'Class does not exist!';
                $this->classes->backend_settings_users = class_exists('DOPBSPBackEndSettingsUsers') ? new DOPBSPBackEndSettingsUsers():'Class does not exist!';

                /*
                 * Initialize shortcodes classes.
                 */
                $this->classes->backend_shortcodes = class_exists('DOPBSPBackEndShortcodes') ? new DOPBSPBackEndShortcodes():'Class does not exist!';

                /*
                 * Initialize SMSes classes.
                 */
                $this->classes->backend_smses = class_exists('DOPBSPBackEndSmses') ? new DOPBSPBackEndSmses():'Class does not exist!';
                $this->classes->backend_sms = class_exists('DOPBSPBackEndSms') ? new DOPBSPBackEndSms():'Class does not exist!';
                /*
                 * Initialize templates classes.
                 */
                $this->classes->backend_templates = class_exists('DOPBSPBackEndTemplates') ? new DOPBSPBackEndTemplates():'Class does not exist!';
                
                /*
                 * Initialize themes classes.
                 */
                $this->classes->backend_themes = class_exists('DOPBSPBackEndThemes') ? new DOPBSPBackEndThemes():'Class does not exist!';
                
                /*
                 * Initialize tools classes.
                 */
                $this->classes->backend_tools = class_exists('DOPBSPBackEndTools') ? new DOPBSPBackEndTools():'Class does not exist!';
                $this->classes->backend_tools_repair_calendars_settings = class_exists('DOPBSPBackEndToolsRepairCalendarsSettings') ? new DOPBSPBackEndToolsRepairCalendarsSettings():'Class does not exist!';
                $this->classes->backend_tools_repair_database_text = class_exists('DOPBSPBackEndToolsRepairDatabaseText') ? new DOPBSPBackEndToolsRepairDatabaseText():'Class does not exist!';
                $this->classes->backend_tools_repair_search_settings = class_exists('DOPBSPBackEndToolsRepairSearchSettings') ? new DOPBSPBackEndToolsRepairSearchSettings():'Class does not exist!';
                
                /*
                 * Initialize translation classes, except main class.
                 */
                $this->classes->backend_translation = class_exists('DOPBSPBackEndTranslation') ? new DOPBSPBackEndTranslation():'Class does not exist!';
                
                /*
                 * Initialize update classes.
                 */
                $this->classes->update = class_exists('DOPBSPUpdate') ? new DOPBSPUpdate():'Class does not exist!';
            }
            
            /*
             * Initialize back end.
             */
            function initBackEnd(){
                /*
                 * Set role action for current user.
                 */
                $user_roles = array_values(wp_get_current_user()->roles);
                
                switch ($user_roles[0]){
                    case 'administrator':
                        $this->vars->role_action = 'manage_options';
                        break;
                    case 'author':
                        $this->vars->role_action = 'publish_posts';
                        break;
                    case 'contributor':
                        $this->vars->role_action = 'edit_posts';
                        break;
                    case 'editor':
                        $this->vars->role_action = 'edit_pages';
                        break;
                    case 'subscriber':
                        $this->vars->role_action = 'read';
                        break;
                    default:
                        $this->vars->role_action = $user_roles[0];
                        break;
                }
                
                if (!isset($this->classes->backend_settings_users)){
                    return false;
                }
                
                if (!$this->classes->backend_settings_users->permission(wp_get_current_user()->ID, 'use-booking-system')
                        && !$this->classes->backend_settings_users->permission(wp_get_current_user()->ID, 'use-calendars')){
                    return false;
                }

                /*
                 * Set back end menu.
                 */
                if (function_exists('add_options_page')){
                    add_menu_page($this->text('TITLE'), $this->text('TITLE'), $this->vars->role_action, 'dopbsp', array(&$this->classes->backend_dashboard, 'view'), 'div');
                    add_submenu_page('dopbsp', $this->text('DASHBOARD_TITLE'), $this->text('DASHBOARD_TITLE'), $this->vars->role_action, 'dopbsp', array(&$this->classes->backend_dashboard, 'view'));
                    add_submenu_page('dopbsp', $this->text('CALENDARS_TITLE'), $this->text('CALENDARS_TITLE'), $this->vars->role_action, 'dopbsp-calendars', array(&$this->classes->backend_calendars, 'view'));
                    add_submenu_page('dopbsp', $this->text('LOCATIONS_TITLE'), $this->text('LOCATIONS_TITLE'), $this->vars->role_action, 'dopbsp-locations', array(&$this->classes->backend_locations, 'view'));
                    add_submenu_page('dopbsp', $this->text('SEARCHES_TITLE'), $this->text('SEARCHES_TITLE'), $this->vars->role_action, 'dopbsp-search', array(&$this->classes->backend_searches, 'view'));
                    add_submenu_page('dopbsp', $this->text('RESERVATIONS_TITLE'), $this->text('RESERVATIONS_TITLE'), $this->vars->role_action, 'dopbsp-reservations', array(&$this->classes->backend_reservations, 'view'));
                        DOPBSP_DEVELOPMENT_MODE ? add_submenu_page('dopbsp', $this->text('REVIEWS_TITLE').$this->text('SOON'), $this->text('REVIEWS_TITLE').$this->text('SOON'), $this->vars->role_action, 'dopbsp-reviews', array(&$this->classes->backend_reviews, 'view')):'';
                    add_submenu_page('dopbsp', $this->text('RULES_TITLE'), $this->text('RULES_TITLE'), $this->vars->role_action, 'dopbsp-rules', array(&$this->classes->backend_rules, 'view'));
                    add_submenu_page('dopbsp', $this->text('EXTRAS_TITLE'), $this->text('EXTRAS_TITLE'), $this->vars->role_action, 'dopbsp-extras', array(&$this->classes->backend_extras, 'view'));
                        DOPBSP_DEVELOPMENT_MODE ? add_submenu_page('dopbsp', $this->text('AMENITIES_TITLE').$this->text('SOON'), $this->text('AMENITIES_TITLE').$this->text('SOON'), $this->vars->role_action, 'dopbsp-amenities', array(&$this->classes->backend_amenities, 'view')):'';
                    add_submenu_page('dopbsp', $this->text('DISCOUNTS_TITLE'), $this->text('DISCOUNTS_TITLE'), $this->vars->role_action, 'dopbsp-discounts', array(&$this->classes->backend_discounts, 'view'));
                    add_submenu_page('dopbsp', $this->text('FEES_TITLE'), $this->text('FEES_TITLE'), $this->vars->role_action, 'dopbsp-fees', array(&$this->classes->backend_fees, 'view'));
                    add_submenu_page('dopbsp', $this->text('COUPONS_TITLE'), $this->text('COUPONS_TITLE'), $this->vars->role_action, 'dopbsp-coupons', array(&$this->classes->backend_coupons, 'view'));
                    add_submenu_page('dopbsp', $this->text('FORMS_TITLE'), $this->text('FORMS_TITLE'), $this->vars->role_action, 'dopbsp-forms', array(&$this->classes->backend_forms, 'view'));
                        DOPBSP_DEVELOPMENT_MODE ? add_submenu_page('dopbsp', $this->text('TEMPLATES_TITLE').$this->text('SOON'), $this->text('TEMPLATES_TITLE').$this->text('SOON'), $this->vars->role_action, 'dopbsp-templates', array(&$this->classes->backend_templates, 'view')):'';
                    add_submenu_page('dopbsp', $this->text('EMAILS_TITLE'), $this->text('EMAILS_TITLE'), $this->vars->role_action, 'dopbsp-emails', array(&$this->classes->backend_emails, 'view'));
                    add_submenu_page('dopbsp', $this->text('SMSES_TITLE'), $this->text('SMSES_TITLE'), $this->vars->role_action, 'dopbsp-smses', array(&$this->classes->backend_smses, 'view'));
                    add_submenu_page('dopbsp', $this->text('TRANSLATION_TITLE', 'Translation'), $this->text('TRANSLATION_TITLE', 'Translation'), 'manage_options', 'dopbsp-translation', array(&$this->classes->translation, 'view'));
		        DOPBSP_DEVELOPMENT_MODE ? add_submenu_page('dopbsp', $this->text('MODELS_TITLE'), $this->text('MODELS_TITLE'), 'manage_options', 'dopbsp-models', array(&$this->classes->backend_models, 'view')):'';
                    add_submenu_page('dopbsp', $this->text('SETTINGS_TITLE'), $this->text('SETTINGS_TITLE'), 'manage_options', 'dopbsp-settings', array(&$this->classes->backend_settings, 'view'));
                    add_submenu_page('dopbsp', $this->text('TOOLS_TITLE', 'Tools'), $this->text('TOOLS_TITLE', 'Tools'), 'manage_options', 'dopbsp-tools', array(&$this->classes->backend_tools, 'view'));
                    DOPBSP_CONFIG_VIEW_ADDONS ? add_submenu_page('dopbsp', $this->text('ADDONS_TITLE'), $this->text('ADDONS_TITLE'), 'manage_options', 'dopbsp-addons', array(&$this->classes->backend_addons, 'view')):'';
                    DOPBSP_CONFIG_VIEW_THEMES ? add_submenu_page('dopbsp', $this->text('THEMES_TITLE'), $this->text('THEMES_TITLE'), 'manage_options', 'dopbsp-themes', array(&$this->classes->backend_themes, 'view')):'';
                }
            }
            
            /*
             * Initialize back end AJAX requests.
             */
            function initBackEndAJAX(){
                /*
                 * Addons back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_addons_display', array(&$this->classes->backend_addons, 'display'));
                
                /*
                 * API back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_api_key_reset', array(&$this->classes->backend_api_key, 'reset'));
            
                /*
                 * Businesses back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_businesses_display', array(&$this->classes->backend_businesses, 'display'));
                add_action('wp_ajax_dopbsp_business_display', array(&$this->classes->backend_business, 'display'));
                add_action('wp_ajax_dopbsp_business_add', array(&$this->classes->backend_business, 'add'));
                add_action('wp_ajax_dopbsp_business_edit', array(&$this->classes->backend_business, 'edit'));
                add_action('wp_ajax_dopbsp_business_delete', array(&$this->classes->backend_business, 'delete'));
                
                /*
                 * Calendars back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_calendars_display', array(&$this->classes->backend_calendars, 'display'));
                
                /*
                 * Calendar back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_calendar_get_options', array(&$this->classes->backend_calendar, 'getOptions'));
                add_action('wp_ajax_dopbsp_calendar_add', array(&$this->classes->backend_calendar, 'add'));
                add_action('wp_ajax_dopbsp_calendar_duplicate', array(&$this->classes->backend_calendar, 'duplicate'));
                add_action('wp_ajax_dopbsp_calendar_edit', array(&$this->classes->backend_calendar, 'edit'));
                add_action('wp_ajax_dopbsp_calendar_delete', array(&$this->classes->backend_calendar, 'delete'));
                
                add_action('wp_ajax_dopbsp_calendar_schedule_get', array(&$this->classes->backend_calendar_schedule, 'get'));
                add_action('wp_ajax_dopbsp_calendar_schedule_set', array(&$this->classes->backend_calendar_schedule, 'set'));
                add_action('wp_ajax_dopbsp_calendar_schedule_delete', array(&$this->classes->backend_calendar_schedule, 'delete'));
            
                /*
                 * Coupons back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_coupons_display', array(&$this->classes->backend_coupons, 'display'));
                add_action('wp_ajax_dopbsp_coupon_display', array(&$this->classes->backend_coupon, 'display'));
                add_action('wp_ajax_dopbsp_coupon_add', array(&$this->classes->backend_coupon, 'add'));
                add_action('wp_ajax_dopbsp_coupon_edit', array(&$this->classes->backend_coupon, 'edit'));
                add_action('wp_ajax_dopbsp_coupon_delete', array(&$this->classes->backend_coupon, 'delete'));
                
                /*
                 * Custom posts back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_custom_posts_get', array(&$this->classes->custom_posts_meta, 'get')); 
            
                /*
                 * Discounts back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_discounts_display', array(&$this->classes->backend_discounts, 'display'));
                add_action('wp_ajax_dopbsp_discount_display', array(&$this->classes->backend_discount, 'display'));
                add_action('wp_ajax_dopbsp_discount_add', array(&$this->classes->backend_discount, 'add'));
                add_action('wp_ajax_dopbsp_discount_edit', array(&$this->classes->backend_discount, 'edit'));
                add_action('wp_ajax_dopbsp_discount_delete', array(&$this->classes->backend_discount, 'delete'));

                add_action('wp_ajax_dopbsp_discount_items_sort', array(&$this->classes->backend_discount_items, 'sort'));
                add_action('wp_ajax_dopbsp_discount_item_add', array(&$this->classes->backend_discount_item, 'add'));
                add_action('wp_ajax_dopbsp_discount_item_edit', array(&$this->classes->backend_discount_item, 'edit'));
                add_action('wp_ajax_dopbsp_discount_item_delete', array(&$this->classes->backend_discount_item, 'delete'));
                
                add_action('wp_ajax_dopbsp_discount_item_rules_sort', array(&$this->classes->backend_discount_item_rules, 'sort'));
                add_action('wp_ajax_dopbsp_discount_item_rule_add', array(&$this->classes->backend_discount_item_rule, 'add'));
                add_action('wp_ajax_dopbsp_discount_item_rule_edit', array(&$this->classes->backend_discount_item_rule, 'edit'));
                add_action('wp_ajax_dopbsp_discount_item_rule_delete', array(&$this->classes->backend_discount_item_rule, 'delete'));
            
                /*
                 * Emails back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_emails_display', array(&$this->classes->backend_emails, 'display'));
                add_action('wp_ajax_dopbsp_email_display', array(&$this->classes->backend_email, 'display'));
                add_action('wp_ajax_dopbsp_email_add', array(&$this->classes->backend_email, 'add'));
                add_action('wp_ajax_dopbsp_email_edit', array(&$this->classes->backend_email, 'edit'));
                add_action('wp_ajax_dopbsp_email_delete', array(&$this->classes->backend_email, 'delete'));
            
                /*
                 * Extras back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_extras_display', array(&$this->classes->backend_extras, 'display'));
                add_action('wp_ajax_dopbsp_extra_display', array(&$this->classes->backend_extra, 'display'));
                add_action('wp_ajax_dopbsp_extra_add', array(&$this->classes->backend_extra, 'add'));
                add_action('wp_ajax_dopbsp_extra_edit', array(&$this->classes->backend_extra, 'edit'));
                add_action('wp_ajax_dopbsp_extra_delete', array(&$this->classes->backend_extra, 'delete'));

                add_action('wp_ajax_dopbsp_extra_groups_sort', array(&$this->classes->backend_extra_groups, 'sort'));
                add_action('wp_ajax_dopbsp_extra_group_add', array(&$this->classes->backend_extra_group, 'add'));
                add_action('wp_ajax_dopbsp_extra_group_edit', array(&$this->classes->backend_extra_group, 'edit'));
                add_action('wp_ajax_dopbsp_extra_group_delete', array(&$this->classes->backend_extra_group, 'delete'));
                
                add_action('wp_ajax_dopbsp_extra_group_items_sort', array(&$this->classes->backend_extra_group_items, 'sort'));
                add_action('wp_ajax_dopbsp_extra_group_item_add', array(&$this->classes->backend_extra_group_item, 'add'));
                add_action('wp_ajax_dopbsp_extra_group_item_edit', array(&$this->classes->backend_extra_group_item, 'edit'));
                add_action('wp_ajax_dopbsp_extra_group_item_delete', array(&$this->classes->backend_extra_group_item, 'delete'));
            
                /*
                 * Fees back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_fees_display', array(&$this->classes->backend_fees, 'display'));
                add_action('wp_ajax_dopbsp_fee_display', array(&$this->classes->backend_fee, 'display'));
                add_action('wp_ajax_dopbsp_fee_add', array(&$this->classes->backend_fee, 'add'));
                add_action('wp_ajax_dopbsp_fee_edit', array(&$this->classes->backend_fee, 'edit'));
                add_action('wp_ajax_dopbsp_fee_delete', array(&$this->classes->backend_fee, 'delete'));
                
                /*
                 * Forms back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_forms_display', array(&$this->classes->backend_forms, 'display'));
                add_action('wp_ajax_dopbsp_form_display', array(&$this->classes->backend_form, 'display'));
                add_action('wp_ajax_dopbsp_form_add', array(&$this->classes->backend_form, 'add'));
                add_action('wp_ajax_dopbsp_form_edit', array(&$this->classes->backend_form, 'edit'));
                add_action('wp_ajax_dopbsp_form_delete', array(&$this->classes->backend_form, 'delete'));

                add_action('wp_ajax_dopbsp_form_fields_sort', array(&$this->classes->backend_form_fields, 'sort'));
                add_action('wp_ajax_dopbsp_form_field_add', array(&$this->classes->backend_form_field, 'add'));
                add_action('wp_ajax_dopbsp_form_field_edit', array(&$this->classes->backend_form_field, 'edit'));
                add_action('wp_ajax_dopbsp_form_field_delete', array(&$this->classes->backend_form_field, 'delete'));
                
                add_action('wp_ajax_dopbsp_form_field_select_options_sort', array(&$this->classes->backend_form_field_select_options, 'sort'));
                add_action('wp_ajax_dopbsp_form_field_select_option_add', array(&$this->classes->backend_form_field_select_option, 'add'));
                add_action('wp_ajax_dopbsp_form_field_select_option_edit', array(&$this->classes->backend_form_field_select_option, 'edit'));
                add_action('wp_ajax_dopbsp_form_field_select_option_delete', array(&$this->classes->backend_form_field_select_option, 'delete'));
            
                /*
                 * Languages back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_languages_display', array(&$this->classes->backend_languages, 'display'));
                add_action('wp_ajax_dopbsp_language_change', array(&$this->classes->backend_language, 'change'));
                add_action('wp_ajax_dopbsp_language_enable', array(&$this->classes->backend_language, 'enable'));    
                
                /*
                 * Locations back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_locations_display', array(&$this->classes->backend_locations, 'display'));
                add_action('wp_ajax_dopbsp_location_display', array(&$this->classes->backend_location, 'display'));
                add_action('wp_ajax_dopbsp_location_add', array(&$this->classes->backend_location, 'add'));
                add_action('wp_ajax_dopbsp_location_edit', array(&$this->classes->backend_location, 'edit'));
                add_action('wp_ajax_dopbsp_location_delete', array(&$this->classes->backend_location, 'delete'));
                add_action('wp_ajax_dopbsp_location_share', array(&$this->classes->backend_location, 'share'));
            
                /*
                 * Models back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_models_display', array(&$this->classes->backend_models, 'display'));
                add_action('wp_ajax_dopbsp_model_display', array(&$this->classes->backend_model, 'display'));
                add_action('wp_ajax_dopbsp_model_add', array(&$this->classes->backend_model, 'add'));
                add_action('wp_ajax_dopbsp_model_edit', array(&$this->classes->backend_model, 'edit'));
                add_action('wp_ajax_dopbsp_model_delete', array(&$this->classes->backend_model, 'delete'));
            
                /*
                 * Reservations back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_reservations_get', array(&$this->classes->backend_reservations, 'get'));
                add_action('wp_ajax_dopbsp_reservations_add_get_json', array(&$this->classes->backend_reservations_add, 'getJSON'));
                add_action('wp_ajax_dopbsp_reservations_add_book', array(&$this->classes->backend_reservations_add, 'book'));
                add_action('wp_ajax_dopbsp_reservations_import_book', array(&$this->classes->backend_reservations_add, 'import'));
                
                add_action('wp_ajax_dopbsp_reservations_calendar_get_json', array(&$this->classes->backend_reservations_calendar, 'getJSON'));
                add_action('wp_ajax_dopbsp_reservations_calendar_get', array(&$this->classes->backend_reservations_calendar, 'get'));
                
                add_action('wp_ajax_dopbsp_reservations_list_get', array(&$this->classes->backend_reservations_list, 'get'));
                add_action('wp_ajax_dopbsp_reservations_list_print', array(&$this->classes->backend_reservations_list, 'printReservations'));
                
                add_action('wp_ajax_dopbsp_reservation_approve', array(&$this->classes->backend_reservation, 'approve'));
                add_action('wp_ajax_dopbsp_reservation_reject', array(&$this->classes->backend_reservation, 'reject'));
                add_action('wp_ajax_dopbsp_reservation_cancel', array(&$this->classes->backend_reservation, 'cancel'));
                add_action('wp_ajax_dopbsp_reservation_delete', array(&$this->classes->backend_reservation, 'delete'));
                
                add_action('wp_ajax_dopbsp_reservation_form_edit', array(&$this->classes->backend_reservation_form, 'edit'));
            
                /*
                 * Rules back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_rules_display', array(&$this->classes->backend_rules, 'display'));
                add_action('wp_ajax_dopbsp_rule_display', array(&$this->classes->backend_rule, 'display'));
                add_action('wp_ajax_dopbsp_rule_add', array(&$this->classes->backend_rule, 'add'));
                add_action('wp_ajax_dopbsp_rule_edit', array(&$this->classes->backend_rule, 'edit'));
                add_action('wp_ajax_dopbsp_rule_delete', array(&$this->classes->backend_rule, 'delete'));
            
                /*
                 * Search back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_searches_display', array(&$this->classes->backend_searches, 'display'));
                add_action('wp_ajax_dopbsp_search_display', array(&$this->classes->backend_search, 'display'));
                add_action('wp_ajax_dopbsp_search_add', array(&$this->classes->backend_search, 'add'));
                add_action('wp_ajax_dopbsp_search_edit', array(&$this->classes->backend_search, 'edit'));
                add_action('wp_ajax_dopbsp_search_delete', array(&$this->classes->backend_search, 'delete'));
            
                /*
                 * Settings back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_settings_calendar_display', array(&$this->classes->backend_settings_calendar, 'display'));
                add_action('wp_ajax_dopbsp_settings_general_display', array(&$this->classes->backend_settings_general, 'display'));
                add_action('wp_ajax_dopbsp_settings_licences_display', array(&$this->classes->backend_settings_licences, 'display'));
                add_action('wp_ajax_dopbsp_settings_licences_activate', array(&$this->classes->backend_settings_licences, 'activate'));
                add_action('wp_ajax_dopbsp_settings_licences_deactivate', array(&$this->classes->backend_settings_licences, 'deactivate'));
                add_action('wp_ajax_dopbsp_settings_notifications_display', array(&$this->classes->backend_settings_notifications, 'display'));
                add_action('wp_ajax_dopbsp_settings_notifications_test', array(&$this->classes->notifications, 'test'));
                add_action('wp_ajax_dopbsp_settings_payment_gateways_display', array(&$this->classes->backend_settings_payment_gateways, 'display'));
                add_action('wp_ajax_dopbsp_settings_search_display', array(&$this->classes->backend_settings_search, 'display'));
                add_action('wp_ajax_dopbsp_settings_set', array(&$this->classes->backend_settings, 'set'));
                
                /*
                 * Settings users permissions back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_settings_users_display', array(&$this->classes->backend_settings_users, 'display'));
                add_action('wp_ajax_dopbsp_settings_users_get', array(&$this->classes->backend_settings_users, 'get'));
                add_action('wp_ajax_dopbsp_settings_users_set', array(&$this->classes->backend_settings_users, 'set'));
                add_action('wp_ajax_dopbsp_settings_users_display_calendar', array(&$this->classes->backend_settings_users, 'displayCalendar'));
                add_action('wp_ajax_dopbsp_settings_users_set_calendar', array(&$this->classes->backend_settings_users, 'setCalendar'));
                
                /*
                 * SMSes back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_smses_display', array(&$this->classes->backend_smses, 'display'));
                add_action('wp_ajax_dopbsp_sms_display', array(&$this->classes->backend_sms, 'display'));
                add_action('wp_ajax_dopbsp_sms_add', array(&$this->classes->backend_sms, 'add'));
                add_action('wp_ajax_dopbsp_sms_edit', array(&$this->classes->backend_sms, 'edit'));
                add_action('wp_ajax_dopbsp_sms_delete', array(&$this->classes->backend_sms, 'delete'));
                
                /*
                 * Themes back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_themes_display', array(&$this->classes->backend_themes, 'display'));
            
                /*
                 * Tools back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_tools_repair_calendars_settings_display', array(&$this->classes->backend_tools_repair_calendars_settings, 'display'));
                add_action('wp_ajax_dopbsp_tools_repair_calendars_settings_clean', array(&$this->classes->backend_tools_repair_calendars_settings, 'clean'));
                add_action('wp_ajax_dopbsp_tools_repair_calendars_settings_get', array(&$this->classes->backend_tools_repair_calendars_settings, 'get'));
                add_action('wp_ajax_dopbsp_tools_repair_calendars_settings_set', array(&$this->classes->backend_tools_repair_calendars_settings, 'set'));
                add_action('wp_ajax_dopbsp_tools_repair_database_text_set', array(&$this->classes->backend_tools_repair_database_text, 'set'));
                add_action('wp_ajax_dopbsp_tools_repair_search_settings_display', array(&$this->classes->backend_tools_repair_search_settings, 'display'));
                add_action('wp_ajax_dopbsp_tools_repair_search_settings_clean', array(&$this->classes->backend_tools_repair_search_settings, 'clean'));
                add_action('wp_ajax_dopbsp_tools_repair_search_settings_get', array(&$this->classes->backend_tools_repair_search_settings, 'get'));
                add_action('wp_ajax_dopbsp_tools_repair_search_settings_set', array(&$this->classes->backend_tools_repair_search_settings, 'set'));
                
                /*
                 * Translation back end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_translation_display', array(&$this->classes->backend_translation, 'display'));
                add_action('wp_ajax_dopbsp_translation_edit', array(&$this->classes->backend_translation, 'edit'));
                add_action('wp_ajax_dopbsp_translation_reset', array(&$this->classes->backend_translation, 'reset'));
                add_action('wp_ajax_dopbsp_translation_check', array(&$this->classes->translation, 'check'));
            }
            
            /*
             * Initialize front end AJAX requests. 
             */
            function initFrontEndAJAX(){
                /*
                 * Calendar front end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_calendar_schedule_get', array(&$this->classes->backend_calendar_schedule, 'get'));
                add_action('wp_ajax_nopriv_dopbsp_calendar_schedule_get', array(&$this->classes->backend_calendar_schedule, 'get'));
                
                /*
                 * Coupons front end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_coupons_verify', array(&$this->classes->frontend_coupons, 'verify'));
                add_action('wp_ajax_nopriv_dopbsp_coupons_verify', array(&$this->classes->frontend_coupons, 'verify'));
                
                /*
                 * Reservations front end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_reservations_book', array(&$this->classes->frontend_reservations, 'book'));
                add_action('wp_ajax_nopriv_dopbsp_reservations_book', array(&$this->classes->frontend_reservations, 'book'));
                
                /*
                 * Search front end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_search_results_get', array(&$this->classes->frontend_search_results, 'get'));
                add_action('wp_ajax_nopriv_dopbsp_search_results_get', array(&$this->classes->frontend_search_results, 'get'));
                
                /*
                 * Search front end AJAX requests.
                 */
                add_action('wp_ajax_dopbsp_search_widget_get', array(&$this->classes->frontend_search_results, 'getWidget'));
                add_action('wp_ajax_nopriv_dopbsp_search_widget_get', array(&$this->classes->frontend_search_results, 'getWidget'));
            }
            
            /*
             * Get text.
             * 
             * @param key (string): translation text key
             * 
             * @return a string 
             */
            function text($key,
                          $fallback = '!'){
                switch ($key){
                    case 'BETA':
                        $prefix = '&nbsp;<em class="dopbsp-small dopbsp-beta">';
                        $suffix = '</em>';
                        break;
                    case 'BETA_TITLE':
                        $prefix = '<em class="dopbsp-beta">';
                        $suffix = '</em>';
                        break;
                    case 'SOON':
                        $prefix = '&nbsp;<em class="dopbsp-small dopbsp-soon">';
                        $suffix = '</em>';
                        break;
                    case 'SOON_TITLE':
                        $prefix = '<em class="dopbsp-soon">';
                        $suffix = '</em>';
                        break;
                    default:
                        $prefix = '';
                        $suffix = '';
                }
                
                return isset($this->vars->translation_text[$key]) ? $prefix.$this->vars->translation_text[$key].$suffix:$fallback;
            }
        }
        $DOPBSP = new DOPBSP();
    }
    
    /*
     * Delete all plugin data if you uninstall it. IMPORTANT! The function needs to be in this file.
     */
    
    if (!function_exists('DOPBSPUninstall')){
	function DOPBSPUninstall(){
	    if (DOPBSP_CONFIG_DELETE_DATA_ON_DELETE){
		global $wpdb;
		global $wp_roles;

		/*
		 * Delete database tables.
		 */
		$tables = $wpdb->get_results('SHOW TABLES');

		foreach ($tables as $table){
		    $object_name = 'Tables_in_'.DB_NAME;
		    $table_name = $table->$object_name;

		    if (strrpos($table_name, 'dopbsp_') !== false){
			$wpdb->query('DROP TABLE IF EXISTS '.$table_name);
		    }
		}

		/*
		 * Delete options.
		 */
		delete_option('DOPBSP_db_version');
		delete_option('DOPBSP_db_version_api_keys');
		delete_option('DOPBSP_db_version_availability');
		delete_option('DOPBSP_db_version_availability_no');
		delete_option('DOPBSP_db_version_availability_price');
		delete_option('DOPBSP_db_version_calendars');
		delete_option('DOPBSP_db_version_coupons');
		delete_option('DOPBSP_db_version_days');
		delete_option('DOPBSP_db_version_days_available');
		delete_option('DOPBSP_db_version_days_unavailable');
		delete_option('DOPBSP_db_version_discounts');
		delete_option('DOPBSP_db_version_discounts_items');
		delete_option('DOPBSP_db_version_discounts_items_rules');
		delete_option('DOPBSP_db_version_emails');
		delete_option('DOPBSP_db_version_emails_translation');
		delete_option('DOPBSP_db_version_extras');
		delete_option('DOPBSP_db_version_extras_groups');
		delete_option('DOPBSP_db_version_extras_groups_items');
		delete_option('DOPBSP_db_version_fees');
		delete_option('DOPBSP_db_version_forms');
		delete_option('DOPBSP_db_version_forms_fields');
		delete_option('DOPBSP_db_version_forms_select_options');
		delete_option('DOPBSP_db_version_languages');
		delete_option('DOPBSP_db_version_locations');
		delete_option('DOPBSP_db_version_models');
		delete_option('DOPBSP_db_version_reservations');
		delete_option('DOPBSP_db_version_rules');
		delete_option('DOPBSP_db_version_searches');
		delete_option('DOPBSP_db_version_settings');
		delete_option('DOPBSP_db_version_settings_calendar');
		delete_option('DOPBSP_db_version_settings_notifications');
		delete_option('DOPBSP_db_version_settings_payment');
		delete_option('DOPBSP_db_version_settings_search');
                delete_option('DOPBSP_db_version_smses');
		delete_option('DOPBSP_db_version_smses_translation');
		delete_option('DOPBSP_db_version_translation');
		delete_option('DOPBSP_db_version_woocommerce'); // This needs to be here.
		delete_option('DOPBSP_view_pro');
                delete_option('DOPBSP_request_rating');
                
		/*
		 * Delete user options.
		 */
		$roles = $wp_roles->get_names();

		while ($data = current($roles)){
		    delete_option('DOPBSP_users_permissions_'.key($roles));
		    delete_option('DOPBSP_users_permissions_custom_posts_'.key($roles));
		    next($roles);                        
		}

		/*
		 * Delete user meta.
		 */
		$users = get_users(array('blog_id' => $GLOBALS['blog_id'],
					 'count_total' => false,
					 'exclude' => array(),
					 'fields' => 'all',
					 'include' => array(),
					 'meta_compare' => '',
					 'meta_key' => '',
					 'meta_query' => array(),
					 'meta_value' => '',
					 'number' => '',
					 'offset' => '',
					 'order' => 'ASC',
					 'orderby' => 'login',
					 'role' => '',
					 'search' => '',
					 'who' => ''));


		foreach ($users as $user){
		    delete_user_meta($user->ID, 'DOPBSP_permissions_calendars');
		    delete_user_meta($user->ID, 'DOPBSP_backend_language');
		    delete_user_meta($user->ID, 'DOPBSP_permissions_view');
		    delete_user_meta($user->ID, 'DOPBSP_permissions_use');
		    delete_user_meta($user->ID, 'DOPBSP_permissions_custom_posts');
		}
	    }
	}
    }
          
    /*
     * Hook uninstall function. The registration needs to be in this file.
     */
    register_uninstall_hook(__FILE__, 'DOPBSPUninstall');  
 
 // Files not included errors handler.
    
    /*
     * Booking System PRO errors handler PHP class. IMPORTANT! The class needs to be in this file.
     */
    class DOPBSPErrorsHandler{
        static $IGNORE_DEPRECATED = true;

        /*
         * Start redirecting PHP errors.
         * 
         * @param level (integer): PHP error level to catch (Default = E_ALL & ~E_DEPRECATED)
         */
        static function start($level = null){
            if ($level == null){
                if (defined('E_DEPRECATED')){
                    $level = E_ALL & ~E_DEPRECATED;
                }
                else{
                    $level = E_ALL;
                    self::$IGNORE_DEPRECATED = true;
                }
            }
            set_error_handler(array('DOPBSPErrorsHandler', 'handle'), $level);
        }

        /*
         * Stop redirecting PHP errors.
         */
        static function finish(){
            restore_error_handler();
        }

        /*
         * Handle error exceptions.
         *
         * @param code (string)
         * @param string (string)
         * @param file (string)
         * @param line (string)
         * @param context (string)
         * 
         * @return true if no errors else the errors object
         */
        static function handle($code,
                               $string,
                               $file,
                               $line,
                               $context){
            if (error_reporting() == 0){
                return;
            }

            if (self::$IGNORE_DEPRECATED 
                    && strpos($string, 'deprecated') === true){
                return true;
            }
            throw new Exception($string, $code);
        }
    }
    
    /*
     * Message to be displayed if not all PHP files are loaded. IMPORTANT! The function needs to be in this file.
     */
    function dopbspMissingFiles(){
        $error = array();
        
        array_push($error, '<div class="update-nag">');
        array_push($error, '  <p>WARNING for Pinpoint Booking System! Not all PHP files needed to run the plugin are on the server, in folder <strong>wp-content/plugins/dopbsp/</strong>. If you are installing or updating the plugin using FTP, please allow all files to upload, or try to upload them again. If you think all files are on the server and this message still exist, please contact us on our <a href="http://envato-support.dotonpaper.net/" target="_blank">Support Forum</a>.</p>');
        array_push($error, '</div>');
        
        echo implode('', $error);
    }
    
    /*
     * Remove after adding to CodeCanyon.
     */
//    add_action('admin_notices', 'dopbspMoving');
    
    function dopbspMoving(){
        $error = array();
        
        array_push($error, '<div class="updated notice dopbsp-notice is-dismissible">');
        array_push($error, '  <p>');
        array_push($error, '    <h3>Important announcement regarding Pinpont Booking System :)</h3>');
        array_push($error, '    <br />');
        array_push($error, '    Pinpoint will not be available on CodeCanyon in the near future ... but we still intend to develop our booking system and offer updates and support to our current and future clients.');
        array_push($error, '    <br /><br />');
        array_push($error, '    <a href="https://pinpoint.world/wordpress-booking/roadmap/?utm_source=WordPress&utm_medium=plugin%20pro" target="_blank">Read more</a> about the reasoning behind this decision, what to do if you are an existing client to receive updates and support, or what benefits (NO VAT, multiple licences, ...) you can have if you decide to buy from our shop.');
        array_push($error, '  </p>');
        array_push($error, '</div>');
        
        echo implode('', $error);
    }