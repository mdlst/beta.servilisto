function theChampLinkedInOnLoad() {
    theChampDisplayLoginIcon(document, ["theChampLinkedinButton", "theChampLinkedinLogin"])
}
IN.Event.on(IN, "auth", function () {
    //theChampLoadingIcon();
    IN.API.Profile("me").fields(["email-address", "id", "picture-urls::(original)", "first-name", "last-name", "headline", "picture-url", "public-profile-url", "num-connections"]).result(function (e) {
        if (e.values[0].id && e.values[0].id != "") {
            theChampCallAjax(function () {
                theChampAjaxUserAuth(e.values[0], "linkedin")
            })
        }
    })
})