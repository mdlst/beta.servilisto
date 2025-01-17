function theChampGoogleOnLoad() {
    theChampDisplayLoginIcon(document, ["theChampGoogleButton", "theChampGoogleLogin"])
}
function theChampInitializeGPLogin() {
    gapi.auth.signIn({
        callback: theChampGPSignInCallback,
        clientid: theChampGoogleKey,
        cookiepolicy: "single_host_origin",
        scope: "profile email"
    })
}
function theChampGPSignInCallback(e) {
    e.status.signed_in ? gapi.client.load("plus", "v1", function () {
        e.access_token ? theChampGetProfile() : ''
    }) : ''
}
function theChampGetProfile() {
    theChampLoadingIcon_goo();
    var e = gapi.client.plus.people.get({userId: "me"});
    e.execute(function (e) {
        return e.error ? void("Access Not Configured. Please use Google Developers Console to activate the API for your project." == e.message && (alert(theChampGoogleErrorMessage), window.open("http://support.heateor.com/how-to-get-google-plus-client-id/"))) : void(e.id && theChampCallAjax(function () {
            theChampAjaxUserAuth(e, "google")
        }))
    })
}
!function () {
    var e = document.createElement("script");
    e.type = "text/javascript", e.async = !0, e.src = "https://apis.google.com/js/client:platform.js?onload=theChampGoogleOnLoad";
    var o = document.getElementsByTagName("script")[0];
    o.parentNode.insertBefore(e, o)
}();