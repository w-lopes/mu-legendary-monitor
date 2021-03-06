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
                    <li class="tab col s6"><a href="#levels">Characters</a></li>
                    <li class="tab col s6"><a href="#account">Add Account</a></li>
                </ul>
            </div>
            <div id="levels" class="col offset-l1 l10 s12">
                <h6>It will ignore chars with level < 200 and without RR ou RB.</h6>
                <small class="grey-text">it may take a few minutes to synchronize with server data!</small>
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
        window["ignores"] = JSON.parse(window.localStorage["ignores"] || "{}");
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

                        var ign     = document.createElement("i");
                        var li      = document.createElement("li");
                        var icon    = document.createElement("i");
                        var name    = document.createElement("div");
                        var namelbl = document.createElement("span");
                        var body    = document.createElement("div");

                        li.id = "char-" + info.Name;

                        icon.className   = "material-icons";
                        icon.textContent = "trending_up";
                        ign.className    = "material-icons left";
                        ign.textContent  = "remove_red_eye";
                        ign.title        = "Ignore/Restore";
                        name.title       = info.Name + " is leveling...";
                        
                        name.className     = "collapsible-header";

                        ign.addEventListener("click", toggleIgnore(info.Name));
                        
                        console.log(ignores)
                        if (typeof ignores[info.Name] === "undefined") {
                            if (info.Level == 400 && info.MasterLevel == 350 && info.ResetCount == 10 && info.Rebirth == 1) {
                                name.className   += " grey darken-3 white-text ";
                                icon.textContent  = "done_all";
                                name.title        = "Mastered!";
                                icon.className   += " pulse ";
                            } else if (info.Level == 400 && info.MasterLevel == 350 && info.ResetCount == 10) {
                                name.className   += " green accent-2 pulse ";
                                icon.textContent  = "replay";
                                name.title        = "Rebirth needed";
                            } else if (info.Level == 400 && info.ResetCount != 10) {
                                icon.textContent  = "refresh";
                                name.className   += " blue lighten-4 pulse ";
                                name.title        = "Reset needed";
                            }
                        } else {
                            icon.textContent  = "perm_identity";
                            name.className   += " white grey-text lighten-5 ";
                            name.title        = "Reset needed";
                        }

                        namelbl.innerHTML  = "<b>" + info.Name + "</b> [" + info.Level + " + " + info.MasterLevel + "]";
                        namelbl.innerHTML += "<small style='margin-left: 5px'>[RR: " + info.ResetCount + " RB: " + info.Rebirth + "]</small>";

                        body.className = "collapsible-body";

                        body.innerHTML = 
                            "<b>Class: </b>" + ClassListDef[info.Class] + "<br/>" +
                            "<b>Level: </b>" + info.Level + "<br/>" +
                            "<b>MasterLevel: </b>" + info.MasterLevel + "<br/>" +
                            "<b>Reset: </b>" + info.ResetCount + "<br/>" +
                            "<b>Rebirth: </b>" + info.Rebirth + "<br/>" +
                            "<hr/>" +
                            "<b>WCoin: </b>" + info.coins.WCoin + "<br/>" +
                            "<b>HuntCoin: </b>" + info.coins.HCoin + "<br/>" +
                            "<b>GoblinPoint: </b>" + info.coins.GPoint + "<br/>";

                        name.appendChild(ign);
                        name.appendChild(icon);
                        name.appendChild(namelbl);
                        li.appendChild(name);
                        li.appendChild(body);
                        container.appendChild(li);
                    }

                    M.Collapsible.init(document.querySelectorAll('.collapsible'), {});

                    var update_time = Math.floor(Math.random() * (30000 - 10000 + 1)) + 10000;
                    window.setTimeout(loadLevels, update_time);
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

        function toggleIgnore(nick) {
            return function(e) {
                e.stopPropagation();
                if (typeof ignores[nick] === "undefined") {
                    ignores[nick] = true;
                } else {
                    delete ignores[nick];
                }
                console.log(ignores);
                window.localStorage["ignores"] = JSON.stringify(ignores);
                window.location.reload();
            };
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