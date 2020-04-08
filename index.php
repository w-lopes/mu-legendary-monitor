<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mu Legendary Monitoring (unnoficial)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="https://mulegendary.net/assets/app/img/favicon.ico" />
    <style>
        .footer {
            position: fixed;
            bottom: 5px;
            left: 5px;
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s4"><a href="#levels">Levels</a></li>
                    <li class="tab col s4"><a href="#coins">Coins</a></li>
                    <li class="tab col s4"><a href="#account">Add Account</a></li>
                </ul>
            </div>
            <div id="levels" class="col offset-l1 l10 s12">
                <h6>It will ignore chars with level < 200 and without RR ou RB.</h6>
                <small class="grey-text">May take some minutes to update!</small>
                <ul class="collapsible"></ul>
            </div>
            <div id="coins" class="col offset-l1 l10 s12">
                <h6>It will ignore chars with level < 200 and without RR ou RB.</h6>
                <small class="grey-text">May take some minutes to update!</small>
                <ul class="collapsible"></ul>
            </div>
            <div id="account" class="col offset-l1 l10 s12">
                <form onsubmit="return login();">
                    <div class="row">
                        <div class="input-field col s6">
                            <input placeholder="Username" id="username" type="text" required="" class="validate" autocomplete="off">
                            <label for="first_name">Username *</label>
                        </div>
                        <div class="input-field col s6">
                            <input placeholder="**********" id="password" type="password" required="" class="validate" autocomplete="off">
                            <label for="password">Password *</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="footer">Unnoficial <a href="https://mulegendary.net" target="_blank">Mu Legendary</a> account monitoring</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        window.addEventListener("load", function() {
            M.Tabs.init(document.querySelectorAll(".tabs"), {});


            var ClassListDef = [
                "Dark Wizard",
                "Soul Master",
                "Grand Master",
                "Soul Wizard",
                "Dark Knight",
                "Blade Knight",
                "Blade Master",
                "Dragon Knight",
                "Elf",
                "Muse Elf",
                "High Elf",
                "Noble Elf",
                "Magic Gladiator",
                "Duel Master",
                "Magic Knight",
                "Dark Lord",
                "Lord Emperor",
                "Empire Lord",
                "Summoner",
                "Blood Summoner",
                "Dimension Master",
                "Dimension Summoner",
                "Rage Fighter",
                "Fist Master",
                "Fist Blazer",
                "Glow Lancer",
                "Mirage Lancer",
                "Shining Lancer"
            ];

            ;(function loadLevels() {
                call("levels", [], function(data) {
                    var levels = document.querySelector("#levels")
                    container  = levels.querySelector(".collapsible");

                    container.innerHTML = "";

                    for (var char in data) {
                        var info    = data[char];

                        if (info.Level < 200 && !info.ResetCount && !info.Rebirth) {
                            continue;
                        }

                        var li      = document.createElement("li");
                        var icon    = document.createElement("i");
                        var name    = document.createElement("div");
                        var namelbl = document.createElement("span");
                        var body    = document.createElement("div");

                        icon.className   = "material-icons";
                        icon.textContent = "person";

                        name.className    = "collapsible-header";
                        namelbl.innerHTML = "<b>" + info.Name + "</b> [" + info.Level + " + " + info.MasterLevel + "]";

                        body.className = "collapsible-body";

                        body.innerHTML = 
                            "<b>Class: </b>" + ClassListDef[info.Class] + "<br/>" +
                            "<b>Level: </b>" + info.Level + "<br/>" +
                            "<b>MasterLevel: </b>" + info.MasterLevel + "<br/>" +
                            "<b>Reset: </b>" + info.ResetCount + "<br/>" +
                            "<b>Rebirth: </b>" + info.Rebirth + "<br/>";

                        name.appendChild(icon);
                        name.appendChild(namelbl);
                        li.appendChild(name);
                        li.appendChild(body);
                        container.appendChild(li);
                    }

                    M.Collapsible.init(document.querySelectorAll('.collapsible'), {});

                    window.setTimeout(loadLevels, 10000);
                });
            })();

            ;(function loadCoins() {
                call("coins", [], function(data) {
                    var coins = document.querySelector("#coins")
                    container = coins.querySelector(".collapsible");

                    container.innerHTML = "";

                    for (var char in data) {
                        var info    = data[char];

                        if (info.Level < 200 && !info.ResetCount && !info.Rebirth) {
                            continue;
                        }

                        var li      = document.createElement("li");
                        var icon    = document.createElement("i");
                        var name    = document.createElement("div");
                        var namelbl = document.createElement("span");
                        var body    = document.createElement("div");

                        icon.className   = "material-icons";
                        icon.textContent = "person";

                        name.className    = "collapsible-header";
                        namelbl.innerHTML = "<b>" + info.Name + "</b>";

                        body.className = "collapsible-body";

                        body.innerHTML = 
                            "<b>WCoin: </b>" + info.WCoin + "<br/>" +
                            "<b>HuntCoin: </b>" + info.HCoin + "<br/>" +
                            "<b>GoblinPoints: </b>" + info.GPoint + "<br/>";

                        name.appendChild(icon);
                        name.appendChild(namelbl);
                        li.appendChild(name);
                        li.appendChild(body);
                        container.appendChild(li);
                    }

                    M.Collapsible.init(document.querySelectorAll('.collapsible'), {});

                    window.setTimeout(loadCoins, 30000);
                });
            })();
        });

        function login() {
            var username = document.querySelector("#username").value;
            var password = document.querySelector("#password").value;
            call("login", [username, password], function(data) {
                M.toast({
                    html: "Account saved!",
                    classes: "green white-text"
                });
                document.querySelector("#username").value = "";
                document.querySelector("#password").value = "";
            });
            return false;
        }

        function call(method, params, callback) {
            callback = callback || function() {};
            var body = {
                method: method,
                params: params
            };
            fetch("mu.php", {
                method: "POST",
                body: JSON.stringify(body)
            }).then(function(response) {
                response.json().then(function(json) {
                    if (json.error) {
                        M.toast({
                            html: json.error,
                            classes: "red white-text"
                        });
                        return;
                    }
                    callback(json.data);
                });
            }).catch(function(err) {
                M.toast({
                    html: "Error!",
                    classes: "red white-text"
                });
            });
        }
    </script>
</body>

</html>