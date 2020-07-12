// Define the command.

var FCKTad = function( name )
{
  this.Name = name ;
  this.EditMode = FCK.EditMode;
}

FCKTad.prototype.Execute = function()
{
  var oPageBreak = FCK.InsertHtml('--summary--');
}

FCKTad.prototype.GetState = function()
{
  return FCK_TRISTATE_OFF ;
}

// Register the Drupal tag commands.
FCKCommands.RegisterCommand( 'summary', new FCKTad( 'PageBreak' ) ) ;

// Create the Drupal tag buttons.
var oTadItem = new FCKToolbarButton( 'summary', 'PageBreak', null, FCK_TOOLBARITEM_ONLYICON, true, true ) ;
oTadItem.IconPath = FCKPlugins.Items['summary'].Path + 'summary.gif';
FCKToolbarItems.RegisterItem( 'summary', oTadItem ) ;