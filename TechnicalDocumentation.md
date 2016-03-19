How To Write a Social Application Using The Open Face Framework
7/2008

## Introduction ##
_Social applications_ are a new breed of software application launched when Facebook opened its API in 2007.  In order to develop a Facebook application, a developer must be proficient in several languages including PHP (or Java or C#) and JavaScript. In addition, the developer has to learn new technologies including FBML, FQL, Mock AJAX. There is an opportunity to significantly reduce the amount of effort involved in the initial learning and development as well as on-going maintenance of these applications. There are several other benefits, such as sharing information across multiple social networks.

In addition, OpenSocial is a strong alternative to the Facebook API but requires a completely different application architecture. Developers find it time-consuming to support both APIs in one application. This is analogous to the multi-platform support challenges of UNIX. There is no easy solution to help ease this pain and we feel there is a significant opportunity to solve this problem.

The Openface Framework is a PHP framework (Java in process) used for building applications that run on Facebook and OpenSocial platforms. Openface is a set of libraries that you add into your PHP application that enables the same source code to run inside different social network containers including Facebook, Bebo and Open Social containers like MySpace, Hi5 & Orkut.

### Social Application Basics ###
For the most part, a social application is similar to any other web application that runs inside a web browser.  Some of the key differences between social applications and general web applications include:

  * A general web application can be built using any industry standard technology.  A social application may have to use technologies developed by the container.  Some industry standard software may not be used by social applications.
  * A general web application manages its authentication.  A social application runs inside a social network container that manages authentication.
  * A general web application manages its user data.  A social application runs inside a social network container that manages the userís social data which can be accessed by the social application via a set of API provided by the container.  In addition,, the social applicationís access to the userís social data is governed by the Terms of Service defined by the container.
  * A general web application has control of the full extent of the web browser.  A social application runs inside the canvas allowed by the container.
  * A general web application can place advertisement anywhere.  A social application can only place advertisement in the areas designated by the container.
  * A general web application has a lot of flexibility in its user interface design.  A social application needs to conform to the ënormí expected by members of the container.
The Open Face Framework was developed to cope with these differences.  Embedded inside the Open Face Framework is a set of engineering ëbest practiceí learned from real world application development experience.
We suggest the following 5 step process:

  1. Plan the Layout of your App
  1. Install the Openface library
  1. Implement the User Interface for your App
  1. Setup/Configure Openface to call your UI
  1. Deploy
  * set up on web host (Joyent, Freehostia)
  * set up on container (Facebook, Hi5)

### Design the User Interface ###
Facebook built several applications (Events, Groups, Photos, Marketplace, Videos) before it opens up its API to outside developers.  These original applications set the user interface layout style expected by Facebook members.  The following screen shot of the Events application illustrates the key elements of the user interface style:

The user interface is a hierarchy with the following elements:

#### Frame ####
The frame is the outermost element controlled by the social application.  Its dimensions may be controlled by the container such that anything outside of the allowed dimensions is clipped and invisible to the user.  The frame determines the general layout of the social application.

#### Canvas ####
Typically a social application has multiple canvases each supporting a ëcommandí.  For example, the Events application has six canvases: Browse Events, Export Events, Upcoming Events, Friendsí Events, Past Events, Birthdays.
Visually the canvas names are rendered in two rows: along the top edge of the frame (Browse Events, Export Events) or as a row of manila folder tabs with the canvas names shown on the tab.  The user clicks on a tab to show the canvas (Upcoming Events, Friendsí Events, Past Events, Birthdays).  Only one canvas can be displayed at any time.

#### Portlet ####
The term ëportletí is borrowed from JSR168 in spirit but the technical implementation details are different.  Portlets are object classes that can be sub-classed to provide the variations and yet share common code.

Inside each canvas is a mosaic of HTML controls and Javascripts which may be remixed in different canvases.  In many cases these HTML controls and Javascripts may be similar but not exactly the same in different canvases.  It is a good practice to organize parts of the canvas into portlets so that you can implement the portlets in an object-oriented style to share code.  Note that portlets can contain other portlets.

#### Dialogs and Control ####
Social applications have some recurring patterns of controls like the Invite Friend dialog that appears in most applications.  These are reusable groups of control whose implementation can be shared among applications.  Dialogs and controls are child elements of portlets, canvases or frames.

### Theory of Operation ###
#### _Social Application Architecture_ ####
#### Facebook ####
Facebook supports two approaches:

  1. Write your social application logic that runs on the server and generates standard HTML + JavaScript that runs inside an iframe.  The iframe approach allows you to deliver an existing web site inside an iframe in the Facebook application.
  1. Write your social application logic that runs on the server and generates FBML, Mock AJAX, FQL and other Facebook-specific technologies.  This approach requires more custom development effort but offer various benefits.

The Open Face Framework is designed to support developing social applications using approach 2.

#### Open Social ####
Open Social supports two approaches:

  1. Write most of your social application logic using JavaScript and calling JavaScript Open Social API.
  1. Write the minimal logic in JavaScript and most of your social application logic on the server side using the Open Social REST API (Open Social v0.8).

The Open Face Framework is designed to support developing social applications using approach 2.

#### _Model-View-Controller Pattern_ ####
The Open Face Framework provides an implementation of the _Model-View-Controller_ pattern.  In essence, Open Face provides a social application

The Open Face Framework provides an implementation of the _Controller_ which receives all HTTP requests (which could be forwarded by the container).  The HTTP request contains several groups of parameters:
  * Parameters as part of an on-screen URL or HTML FORM parameters
  * Parameters provided by the container
  * Parameters injected by Open Face Framework

#### _Controller_ ####
The Open Face Framework provides an implementation of the _Controller_.  The controller invokes the _main frame_ object provided by the application.

All calls to your application are made to `openface/php/index.php` which handles all HTTP requests.  The callback URL of your own application registered with the container needs to be `applicationDir/openface/php/index.php`.  On Facebook this URL is the _Callback URL_ in the _Edit Settings_ screen of the Facebook _Developer_ application.

#### _View_ ####
#### Frame ####
Open Face provides three base classes of frame:

  * **OpfSingleCanvas.php**
> It is the simplest frame with only one child canvas.

  * **OpfMultiCanvas.php**
> It supports a single row of tabs below the application name.

  * **OpfMultiCanvas2.php**
> It supports two rows of canvas labels.  One row is display along the top edge of the frame above the application name.  Another row is shown below the application name.

The application provides a frame class which is a child class of one of the three base classes.

#### Canvas ####
- Any object instances (like Controls, widgets, or most application segments,) will be a subclass of OPFCanvas

DETAILS FOR APPLICATION EXECUTION PATH.

  * All calls to your application are made to `index.php`<sup>1</sup>, and any submission always goes back to index.php or a submission file (<sup>1</sup> - The callback URL of your own application registered with FaceBook needs to be `application/openface/php/index.php`) (IMAGE ATTACHED)
  * GetCanvas gives the ObjectNames of optional canvas controls that determines canvas content
  * GetCanvas2 returns the ObjectNames of the canvas tab-control
  * Every Canvas object requires that the following methods are defined: "getIcon, getTag, getLabel, render"
    * `getIcon` ñreturns the image file name, and For Facebook, - if it is up to 16x16 pixels - will show up to the left of the label in the canvas/Tab
    * `getTag` uses the php variable "tag" for switching from tag to tag. It needs to be url-friendly
    * `getLabel` is a human-readable word, which, for Facebook can be localized by the app based on the FB-user Language Preference.
    * `render` generates the HTML content (fragment), and return as a string.  Let the caller function assemble the different pieces, and render in their own liberty.  (In the future, this technique may gives us the ability to optimize the HTML, tag-translation if someone feels called to code it, or dynamic content. Another function can be getting rid of blank lines or empty spaces to reduce the number of bytes transferred.)
  * The frame (FrameMultiCanvas) has a default Canvas function, called by getDefaultCanvas which returns a tab which points to the default canvas name you set yourself.
  * Optionally, if you are using any additional canvas that is required for the app, use the optional function getMustInstallCanvasTagList.

HOW TO CREATE YOUR FIRST CANVAS

  * We create our sample application using the Portlet Design Pattern, utilizing "getIcon, getTag, getLabel, render" function calls
  * `php\portlets` directory is where all the portlets are placed
  * the frames and canvas are under the `php\views` directory

In the `php\portlets` folder, you can create the portlets that are shared classes and will be used to extend other portlets.  All portlets will eventually be subclasses of _OpfPortlet_.
_OpfPortlet_ has three methods:
  * the portlet objects instanciate as a child of the canvas object (following the jsr168 convention)
  * the portletís render method returns html fragments defining the portlet and is captured in a string, which is then returned as the function return, or
  * invoking the portlet directly

HOW TO ADD MYSQL DATABASE-SUPPORT

  * MySQL does not require the surrogate key as the primary key, but Openface database support does, and it is always called objid.  You benefit from the Database support of Openface by referring to the surrogate key (TableName.ObjID) in every SQL clause.
  * Connecting to the DB, (Opening the Database connection) is an action performed by the Openface Framework once the database controller is instantiated.  This is done using the parameters in the OpfConfig which are mentioned below, in the section on setting up the application on Facebook
  * Database Tables in Openface are composed of classes.  In the php/models directory there is one file per table in the database.
  * Files are conventionally named TableTableName (sentence case) so table "Card" has file "TableCard.php" (TableNames in MySQL _are_ Case Sensitive, so please pay attention to use the correct case)
  * Every table must extend the class OpfDataTable: class TableCard extends OpfDataTable {...}
  * Each column in the table gets defined as a constant value for the variables (TABLENAME, .., .., ..)
  * The GetDBConnect? function is defined in the class "OpfUIObject", which is the parent of OpfCanvas, OpfFrame, OpfPortlets, and basically all the UI objects. So therefore any Application UI class (e.g., PortletCardList) will inherit this function.
  * This is the required parameter to instantiate all the table classes.
  * Any table instance extends OpfDataTable (which is the table root).
  * The Sort Order is defined in the OpfDataTable class, which comes from objid.  This is in contrast with conventional DB practice, where random sorting is the norm.  This default sort order can be modified in the child class via the "setDefaultSortOrder" string variable.  Because it is a string, multiple columns can be defined. Other environmental variables that can be modified (like LimitCount) are in this class def file.

Now we look at a portlet to see why we bother to extend OpfDataTable
  * Instantiate new TableCard (by defining a $table), we call the table method which returns an array where the key = objid, and Value is contained in another array, so the table comes back as a 2-D array where the database values comprise the second array.  The first dimension is referenced by objid, and the second dim is referred by fieldname.  To get this done in native PHP is why we extend this class OpfDataTable, which leads to more optimized code performance.

TO TIE APP WITH OPF

Breaking down your code based on the development stage is useful to separate different development threads.  (Common conventions, like directory structure and naming ñ as in the three primary stages titled "Development", "Demo", & "Production" ñ come from RubyOnRails.)

php Opf directory contents are three files which define the application to OpenFace: (OpfApplication.php, OpfApplicationConfig.php, & OpfHelp.php)
  * opfApplication defines application specifics like [- WE NEED CONCISE EXAMPLES](STEVE.md)
  * opfApplicationConfig defines a number of Constants
  * OpfHelp is a descriptive file where you are able to specify application usage details down to any level necessary

OpfApplication.php is a class of its own, and does not have a parent class. It provides 3 STATIC Functions:
  * getMainFrame - returns an instance of the FrameMultiCanvas, that extends OpfFrameMultiCanvas2
  * registeruser
  * unregisteruser

[PANEL, description coming later, can be used for navigation inside a canvas, just as canvas can serve navigation inside a frame]

So the application code is in the Views using Frame, Canvas and Portlet.  It should be ready to run, once you've deployed it. Need much more specific description of what this means

### Development Setup ###
The following description assumes that you will be writing your application in PHP which does not require a compilation step and no object code is generated.

First create a top-level directory which will be called _applicationDir_ for the rest of this document.  It is a good practice to use the same directory name as the Facebook directory name.  For example, in the sample _Send-A-Card_ application, the top-level directory is called `sendacard`.

Later we will discuss where to install this top-level directory.

#### _The Open Face Framework_ ####
Create a second-level directory called `openface`.  Download the Open Face Framework from http://www.openfaceframework.org into this directory.  This is the only directory used to store files from the Open Face Framework.  All other directories under the top-level directory is used by your application.

#### _Application Directories_ ####
It is recommended that you create the following second-level directories:

`css images images.demo js php xml`

You can place the source files of your application into these directories.  The name of a directory defines the type of file that will be stored in the directory.

#### css ####
All `*.css` files are placed in the _applicationDir_`/css` directory.  Open Face will automatically include every file in this directory in a style tag like this:

`<style type="text/css">app.css</style>`

#### js ####
All `*.js` files are placed in the applicationDir/js directory.  Open Face will automatically include every file in this directory in a script tag like this:

`<script src="app.js"/>`

#### images ####
All graphics files used from development to production are placed in the _applicationDir_`/images` directory.  Open Face provides helper methods to generate HTML <img> tags with the assumption that all images are stored in this directory.<br>
<br>
<h4>images.demo</h4>
Sometimes you may have images used only for demonstration purposes.  These images are not the production images.<br>
<br>
<h4>xml</h4>
Sometimes your application may have xml files too.  For example, Open Social containers require an xml file to register your application.<br>
<br>
<h4>php</h4>
All PHP files are placed in the <i>applicationDir</i><code>/php</code> directory.  In order to manage the complexity, the PHP files are further organized into subdirectories:<br>
<br>
<blockquote><code>actions dialogs models opf portlets views</code></blockquote>

<h4>actions</h4>
When the user clicks on a button to submit the contents of a form, the contents of the form are POSTed to an ëactioní which is invoked.<br>
<br>
<h4>dialogs</h4>
Dialogs are commonly used dialogs like the Invite Friend dialog.<br>
<br>
<h4>models</h4>
Open Face Framework implements the model-view-controller pattern.  The models directory stores the classes that implements the views.<br>
<br>
<h4>opf</h4>
This sub-directory stores configuration files in the syntax of PHP.  These files provide constants for the Openface Framework to do its work.<br>
<br>
<h4>portlets</h4>
<i>Portlet</i> classes are stored here.<br>
<br>
<h4>views</h4>
Open Face Framework implements the model-view-controller pattern.  The views directory stores the classes that implements the views.  Specifically the <i>frame</i> and <i>canvas</i> classes are stored here.<br>
<br>
<h3>Deployment</h3>

<h4><i>Set up on web host</i></h4>
<ul><li>5. (a) - set up on host Joyent/Freehostia<br>
</li><li>5.a (i) on Freehostia, you can have subdomains right in your www root, which is where we put our SendACard directory.<br>
YOU SHOULD NOW HAVE A COMPLETE URL TO REFER TO: e.g.:<br>
Joyent: <a href='http://www.joyent.com[/?/?'>http://www.joyent.com[/?/?</a> - Need FURTHER DETAIL HERE]<br>
Freehostia: <a href='http://sendacard.freehostia.com/fb.dev/openface/php/index.php?opf_postInstall='>http://sendacard.freehostia.com/fb.dev/openface/php/index.php?opf_postInstall=</a>
</li><li>set up on Facebook <a href='WOULD.md'>A LINK BE SUFFICIENT, OR IS MORE DETAIL NEEDED HERE?</a>
</li><li>Copy the entire "sendacard" directory with contents into this folder (whichever host)<br>
</li><li>Go to the OpfApplicationConfig.php and verify that any DATABASE variables are commented for non-DB apps (why is this necessary?), or tied to the correct location for DB Apps.  These variables are dependant on the database and application hosting environment.<br>
</li><li>5.a (ii) on Joyent, you are given a unix-like path where you receive a ./web/public folder, under which the "sendacard" app needs to be copied.</li></ul>

You need to create an account and be informed that your level of<br>
<br>
<h4><i>Set up on Container ñ Facebook</i></h4>
<ul><li>5.(b) -  Add the Developer application (see Facebook for the latest instructions) and register the new app. "Apply for Application Key" generates two variables for you.  These two (API Key & Secret) must be placed in this  OpfApplicationConfig.php file for the application to be recognized by FB<br>
Click "Edit Settings" to change CallBack URL variable which needs to go to "openface\php", where the index.php is located</li></ul>

CanvasPageURL is also created here and placed in the OpfApplicationConfig.php file.<br>
<br>
Ensure that FBML is being used, and not iFrame.  Leave everything else as default<br>
<br>
Verify that the App <i>CAN</i> be added on FB - this enables installation.  This opens up the Installation OPTIONS dialogue:<br>
<br>
<ul><li>Post ADD URL needs to be put in from above: <a href='http://sendacard.freehostia.com/fb.dev/openface/php/index.php?opf_postInstall='>http://sendacard.freehostia.com/fb.dev/openface/php/index.php?opf_postInstall=</a>
</li><li>Post-Remove URL as well: <a href='http://sendacard.freehostia.com/fb.dev/openface/php/index.php?opf_postUninstall='>http://sendacard.freehostia.com/fb.dev/openface/php/index.php?opf_postUninstall=</a></li></ul>

Choose your own preferences, as in "Developer Install only", which keeps normal users away from app, and "Private Installation", which suppresses news feed generation<br>
<br>
NOW we verify that <code>php\MODELS</code> is configured, which handles DATABASE interaction.  This is specific to applications that utilize database interaction.  GiveToPersonAction.php is an example in SendACard app.  If there is no database backend, then the application is ready for execution.  Point your browser to the path set up in 5(b) above.<br>
<br>
<h3>Appendix A: Open Face Framework source files</h3>
The <code>openface</code> directory contains source files from the Open Face Framework. You should not modify any file inside.<br>
<br>
<table><thead><th> css </th><th> Open Face css files </th></thead><tbody>
<tr><td> images </td><td> Open Face graphic image files </td></tr>
<tr><td> js  </td><td> Open Face Javascript files </td></tr>
<tr><td> LICENSE.txt </td><td> Open Face license   </td></tr>
<tr><td> php </td><td> Open Face PHP files. Details are described in the following sections </td></tr></tbody></table>

<h4>openface/php</h4>
<table><thead><th> help.php </th><th> It calls <code>php/opf/OpfHelp.php</code> to launch the help command which is located on the upper right hand corner of the application </th></thead><tbody>
<tr><td> index.php </td><td> This is the main entry point of the application. On Facebook you should register the callback URL as <code>$APP_CALLBACK_URL/openface/php/index.php</code> </td></tr>
<tr><td> OpfConfig.php </td><td> It contains global configuration parameters used by Open Face Framework. The application should not directly reference these constants because they may change </td></tr>
<tr><td> OpfContext.php </td><td> It contains a runtime object used by Open Face Framework. The application should not directly reference this class                      </td></tr>
<tr><td> OpfDebugUtil.php </td><td> It contains some debugging methods. The application may call these methods. NOTE: The author considers this module 'can be improved' and will enhance it in the future </td></tr>
<tr><td> OpfLocalization.php </td><td> It contains localized strings used by Open Face Framework. The application should not directly reference this class                     </td></tr>
<tr><td> OpfSystemProfile.php </td><td> It contains some system parameters used by Open Face Framework. The application should not directly reference this class                </td></tr></tbody></table>

<h4>openface/php/core</h4>
<table><thead><th> OpfCoreWebObject.php </th><th> It is the parent class of some classes in <code>openface/php/views</code>. The application should not directly reference this class </th></thead><tbody></tbody></table>

<h4>openface/php/controllers</h4>
<table><thead><th> OpfController.php </th><th> It is the main controller of Open Face Framework. It will calls <code>OpfApplication::getMainFrame</code> to create a main frame object that renders the application </th></thead><tbody>
<tr><td> OpfControllerPostInstall.php </td><td> It is called after the user installs the application. It calls <code>OpfApplication::registerUser</code>                                                             </td></tr>
<tr><td> OpfControllerPostUninstall.php </td><td> It is called after the user uninstalls the application. It calls <code>OpfApplication::unregisterUser</code>                                                         </td></tr></tbody></table>

<h4>openface/php/views</h4>
<b>Definition</b>

<b>frame</b>

A frame is the outermost user interface element of the application. The Open Face controller renders a 'main' frame that contains the rest of the application.<br>
<br>
A frame contains several user interface elements:<br>
<ul><li>an optional row of hyperlinks at the top with an underline<br>
</li><li>the application name<br>
</li><li>an optional list of buttons<br>
</li><li>an optional row of canvas names<br>
</li><li>a canvas</li></ul>

<b>canvas</b>

Typically an application shows a different canvas depending on the user operation. For example, in the sendACard application, there can be four canvases:<br>
<ul><li>Cards I can select to send<br>
</li><li>Inbox with cards friends sent to me<br>
</li><li>Outbox with cards I sent to friends<br>
</li><li>Cards I have kept</li></ul>

The contents of each canvas can be completely different from each other.<br>
<br>
<b>panel</b>

A canvas can have multiple variations inside. For example, in the Cards I can select to send canvas, cards may be organized by category such as Christmas, birthday, friendship, Thanksgiving, ... etc. Each category can be a <i>panel</i> showing only cards in that category.<br>
<br>
<table><thead><th> OpfUIObject.php </th><th> It is the parent class of other Open Face classes. Application should not directly reference this class </th></thead><tbody>
<tr><td> OpfFrameSingleCanvas.php </td><td> It implements a frame with only one canvas. There is no row of canvas names. Application may subclass from it to create its 'main' frame. This is not typical </td></tr>
<tr><td> OpfFrameMultiCanvas.php </td><td> It implements a frame with a row of canvas names below the application name. Application may subclass from it to create its 'main' frame </td></tr>
<tr><td> OpfFrameMultiCanvas2.php </td><td> It implements a frame with two rows of canvas names: one row at the top and one row below the application name. Application may subclass from it to create its 'main' frame </td></tr>
<tr><td> OpfCanvasHelp.php </td><td> It implements the canvas when the user clicks on help                                                   </td></tr>
<tr><td> OpfCanvasMultiPanel.php </td><td> It implements a canvas that has multiple sub-panels. Application may subclass from it to create a canvas class </td></tr>
<tr><td> OpfCanvas.php   </td><td> It is the parent class of all application canvas classes. Application must subclass from it to create a canvas class </td></tr>
<tr><td> OpfExecuteAction.php </td><td> OpfFrame<code>*</code> calls this class to executes actions. Application may subclass from it to call its actions </td></tr>
<tr><td> OpfPanel.php    </td><td> It is the parent class of all application panel classes. Application must subclass from it to create a panel class </td></tr>
<tr><td> OpfWebParam.php </td><td> It is the parent class of OpfFormParam.php and OpfUrlParam.php. Application should not directly reference this class </td></tr>
<tr><td> OpfFormParam.php </td><td> It is a helper class to construct HTML FORM parameters                                                  </td></tr>
<tr><td> OpfUrlParam.php </td><td> It is a helper class to construct URL parameters typically used in HREF                                 </td></tr></tbody></table>

<h4>openface/php/portlets</h4>
The term <i>portlet</i> has been used in JSR168 and Spring Framework. The portlet concept is borrowed in spirit in the Open Face Framework to refer to a section on the screen with mostly self-contained actions and may be reused in different places of the user interface. Otherwise there is no technical similarity.<br>
<br>
I have found the portlet concept to be of much practical value for several reasons:<br>
<ol><li>It allows the same user-interface logic to be reused in multiple places.<br>
</li><li>It allows subclassing of similar user-interface logic.<br>
</li><li>It allows Open Face to provide more commonly used portlets in the future so the application can subclass and reuse common logic.<br>
<table><thead><th> OpfPortlet.php </th><th> It is the parent class of all application portlet classes. Application must subclass from it to create all Portlet classes </th></thead><tbody>
<tr><td> OpfPortletInvite.php </td><td> It is a commonly used portlet for inviting friends. Application may subclass from it to create a friend invitation portlet </td></tr></li></ol></tbody></table>

<h4>openface/php/controls</h4>
These are helper classes to generate HTML controls. Application may instantiate these classes, call its various population methods to fill in the details and then call its <code>render()</code> method to generate a HTML string fragment.<br>
<br>
<table><thead><th> OpfControlHrefBar.php </th><th> It implements a row of equally-spaced HREF. It is useful for showing a list of panel names </th></thead><tbody>
<tr><td> OpfControlHrefRow.php </td><td> It implements a row of equally-spaced HREF. It is useful for showing a list of panel names </td></tr>
<tr><td> OpfControlRadio.php   </td><td> It generates an array of HTML radio controls                                               </td></tr>
<tr><td> OpfControlSelect.php  </td><td> It generates a HTML SELECT control                                                         </td></tr></tbody></table>

<h4>openface/php/callbacks</h4>
A callback is an asynchronous callback target invoked via an AJAX or Mock AJAX call.<br>
<table><thead><th> OpfCallback.php </th><th> It is the parent class of all application callback classes. Application must subclass it to create a callback class </th></thead><tbody></tbody></table>

<h4>openface/php/actions</h4>
An action is a method executed when a HTTP request is received before rendering the application user interface. OpfExecuteAction.php examines the HTTP request parameters to determine which action should be executed and then calls the action.<br>
<table><thead><th> OpfAction.php </th><th> It is the parent class of all application action classes. Application must subclass it to create a action class </th></thead><tbody></tbody></table>

<h4>openface/php/helpers</h4>
<table><thead><th> OpfHelperHtml.php </th><th> It contains some static methods to generate HTML fragments. The application may call these methods directly </th></thead><tbody>
<tr><td> OpfHelperHtmlSite.php </td><td> It contains some static methods to generate social site-dependentHTML fragments. There is a different file for each social site. The application may call these methods directly </td></tr>
<tr><td> OpfHelperJs.php   </td><td> It contains some static methods to generate Javascript fragments. There is a different file for each social site. The application may call these methods directly </td></tr></tbody></table>

<h4>openface/php/models</h4>
It supports methods of the social network and MySQL tables.<br>
<table><thead><th> OpfSiteWrapper.php </th><th> It wraps the social network api object. There is a different PHP file for each social network. Application may not call this class directly </th></thead><tbody>
<tr><td> OpfDataSource.php  </td><td> It wraps the social network api alls in <code>OpfSiteWrapper.php</code>. Application calls methods in this class to access social network functionality </td></tr>
<tr><td> OpfDataTable.php   </td><td> It provides commonly-used methods when wrapping a MySQL table. Application may subclass from it to create a MySQL table class               </td></tr>
<tr><td> OpfDbConnect.php   </td><td> It provides commonly-used methods when using MySQL. Application may call methods in this class directly                                     </td></tr>
<tr><td> OpfSqlStatement.php </td><td> It is the parent class of the other OpfSql<code>*</code> classes. Application may not call this class directly                              </td></tr>
<tr><td> OpfSqlInsert.php   </td><td> It is a helper class to generate a MySQL INSERT statement. Application may call methods in this class                                       </td></tr>
<tr><td> OpfSqlSelect.php   </td><td> It is a helper class to generate a MySQL SELECT statement. Application may call methods in this class                                       </td></tr>
<tr><td> OpfSqlUpdate.php   </td><td> It is a helper class to generate a MySQL UPDATE statement. Application may call methods in this class                                       </td></tr></tbody></table>

<h4>openface/php/intfc</h4>
It supports various commonly used third-party REST API. More third-party support will be added in the future.<br>
<table><thead><th> OpfGoogleMap.php </th><th> It wraps calls to Google Map in a PHP method </th></thead><tbody></tbody></table>

<h4><i>application callback source files</i></h4>
Application callback files should be placed in the directory <code>php/opf</code>. There are three callback files.<br>
<br>
<b>OpfApplicationConfig.php</b>

It contains a list of constants needed by Open Face Framework.<br>
<ul><li>APPLICATION_TITLE<br>
</li><li>APP_CALLBACK_URL<br>
</li><li>APP_INVOCATION_URL<br>
</li><li>HELP_PAGE<br>
</li><li>IMAGES_DIRECTORY<br>
</li><li>APPLICATION_ICON<br>
</li><li>APPLICATION_SPLASH<br>
</li><li>MY_DATABASE_PATH<br>
</li><li>MY_DATABASE_NAME<br>
</li><li>MY_DATABASE_USER<br>
</li><li>MY_DATABASE_PASSWORD<br>
</li><li>SITE_API_KEY<br>
</li><li>SITE_API_SECRET<br>
</li><li>SITE_USERNAME<br>
</li><li>SITE_PASSWORD<br>
</li><li>SITE_NAME</li></ul>

<b>OpfApplication.php</b>

It must implement the following methods:<br>
<ul><li>static public function getMainFrame()<br>
</li></ul><blockquote>Returns an object which extends any one of <code>OpfFrameSingleCanvas</code>, <code>OpfFrameMultiCanvas2.php</code>, <code>OpfFrameMultiCanvas.php</code>.<br>
</blockquote><ul><li>static function registerUser($uid, $dbConnect, $dataSource)<br>
</li></ul><blockquote>This method is called when the user first installs the application. Typically this method inserts a row in a database table to store the $uid.<br>
</blockquote><ul><li>static function unregisterUser($uid, $dbConnect)<br>
</li></ul><blockquote>This method is called when the user uninstalls the application. Typically this method marks a row in a database table to show that $uid is no longer an application user.</blockquote>

<b>OpfHelp.php</b>

It is a HTML file that displays a universal online help for the user.