var G_INCOMPAT = false;
function GScript(src)
{
    document.write('<' + 'script src="' + src + '"' +' type="text/javascript"><' + '/script>');
}
function GBrowserIsCompatible()
{
    if (G_INCOMPAT) return false;
    if (!window.RegExp) return false;
    var AGENTS = ["opera","msie","safari","firefox","netscape","mozilla"];
    var agent = navigator.userAgent.toLowerCase();
    for (var i = 0; i < AGENTS.length; i++)
    {
        var agentStr = AGENTS[i];
        if (agent.indexOf(agentStr) != -1)
        {
            var versionExpr = new RegExp(agentStr + "[ \/]?([0-9]+(\.[0-9]+)?)");
            var version = 0;
            if (versionExpr.exec(agent) != null)
            {
                version = parseFloat(RegExp.$1);
            }
            if (agentStr == "opera") return version >= 7;
            if (agentStr == "safari") return version >= 125;
            if (agentStr == "msie") return (version >= 5.5 &&agent.indexOf("powerpc") == -1);
            if (agentStr == "netscape") return version > 7;
        }
    }
    return document.getElementById;
}
function GLoad()
{
    G_INCOMPAT = false;
    GLoadApi(["map.php&","map.php&","map.php&","map.php&"], ["map.php&","map.php&","map.php&","map.php&"], ["map.php&","map.php&","map.php&","map.php&"],"","");
}
function GUnload()
{
    if (window.GUnloadApi)
    {
        GUnloadApi();
    }
}
var _mFlags =
{
};
var _mHost = "http://localhost";
var _mUri = "/gmap";
var _mDomain = "localhost";
var _mStaticPath = "http://localhost/gmap/mapfiles/";
var _mTermsUrl = "http://www.google.com/intl/en_ALL/help/terms_local.html";
var _mTerms = "Terms of Use";
var _mMapMode = "Map";
var _mMapModeShort = "Map";
var _mMapError = "We are sorry, but we don\'t have maps at this zoom level for this region.<p>Try zooming out for a broader look.</p>";
var _mSatelliteMode = "Satellite";
var _mSatelliteModeShort = "Sat";
var _mSatelliteError = "We are sorry, but we don\'t have imagery at this zoom level for this region.<p>Try zooming out for a broader look.</p>";
var _mHybridMode = "Hybrid";
var _mHybridModeShort = "Hyb";
var _mSatelliteToken = "fzwq1JKxOOqZ5544t106zcWcMq1ee-Na5UqPjw";
var _mZoomIn = "Zoom In";
var _mZoomOut = "Zoom Out";
var _mZoomSet = "Click to set zoom level";
var _mZoomDrag = "Drag to zoom";
var _mPanWest = "Go left";
var _mPanEast = "Go right";
var _mPanNorth = "Go up";
var _mPanSouth = "Go down";
var _mLastResult = "Return to the last result";
var _mMapCopy = "Map data &#169;2006 ";
var _mSatelliteCopy = "Imagery &#169;2006 ";
var _mGoogleCopy = "&#169;2006 Google";
var _mKilometers = "km";
var _mMiles = "mi";
var _mMeters = "m";
var _mFeet = "ft";
var _mPreferMetric = false;
var _mDecimalPoint = '.';
var _mThousandsSeparator = ',';
var _mUsePrintLink = 'To see all the details that are visible on the screen,use the "Print" link next to the map.';
var _mPrintSorry = '';
var _mMapPrintUrl = 'http://www.google.com/mapprint';
var _mPrint = 'Print';
var _mOverview = 'Overview';
var _mStart = 'Start';
var _mEnd = 'End';
var _mStep = 'Step';
var _mHideAllMaps = 'Hide Maps';
var _mShowAllMaps = 'Show All Maps';
var _mUnHideMaps = 'Show Maps';
var _mShowLargeMap = 'Show original map view.';
var _mmControlTitle = null;
var _mAutocompleteFrom = 'from';
var _mAutocompleteTo = 'to';
var _mAutocompleteNearRe = '^(?:(?:.*?)&#92;s+)(?:(?:in|near|around|close to):?&#92;s+)(.+)$';
var _mSvgEnabled = true;
var _mSvgForced = false;
var _mLogInfoWinExp = 'true';
function GLoadMapsScript()
{
    if (GBrowserIsCompatible())
    {
        GScript("maps2.62.api.js");
    }
}
GLoadMapsScript();
