<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>シャロシコAPI</title>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/pure/0.6.0/pure-min.css">
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://platform.twitter.com/widgets.js" async></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="me" href="https://twitter.com/hnle0">
    <script>
        $(function ($) {
            $('a[href^=#]').click(function (e) {
                var href = this.href;
                href = href.substr(href.indexOf("#"));
                var target = $(href === '#' ? 'html' : href);
                $('body,html').animate({scrollTop: target.offset().top - 40}, 'slow', 'swing');
                return false;
            });
        });
    </script>
</head>
<body>

<div class="splash-container">
    <div class="splash">
        <h1 class="splash-head">シャロシコAPI</h1>

        <p class="splash-subhead">
            シャロシコを観測、計測するだけのAPI.
        </p>

        <p>
            <a href="#doc" class="pure-button pure-button-primary">API Document</a>
        </p>
    </div>
</div>
<div class="splash-container background"></div>
<div class="content-wrapper">
    <div class="content">
        <div class="is-center"><a class="twitter-share-button"
                                  href="https://twitter.com/share"
                                  data-url="https://syaroshico.hinaloe.net/"
                                  data-via="hnle0"
                                  data-related="sudosan"
                                  data-hashtags="シャロシコ"
                                  data-text="シャロシコを数えよう。 ～シャロシコAPI～">
                Tweet
            </a></div>
        <h2 class="content-head is-center" id="doc">Document</h2>
    </div>

    <div class="ribbon l-box-lrg pure-g">
        <div class="l-box-lrg is-center pure-u-1 pure-u-md-1 pure-u-lg-1">
            <h2 class="content-head content-head-ribbon">概要</h2>

            <p>このAPIはTwitter上の公開ツイートからシャロシコ、syaroshi.coに関するツイートをカウントした合計を返すだけの単純なものです。</p>

            <p>※2013年のツイートを起源にカウントしていますが、Twitterの仕様やカウントの仕方等事情により結果には多少の誤差が存在しています。</p>

        </div>
    </div>
    <div class="content">
        <h2 class="content-head is-center">Endpoint</h2>

        <div class="pure-g">

            <div class="l-box-lrg pure-u-1 pure-u-md-3-4 pure-u-lg-5-8">
                <h3>[GET] /api/v1/count.json</h3>
                <h4>パラメーター</h4>
                <small>(GETクエリとかガン無視っすよね？)</small>
                <dl>
                    <dt>(bool) url</dt>
                    <dd>URL(syaroshi.co)をカウント対象に入れるか否か <code>true</code>/<code>false</code></dd>
                    <dt>(bool) shico</dt>
                    <dd>「シャロシコ」(単語) を検索対象に入れるか否か <code>true</code>/<code>false</code></dd>
                    <dt>(bool) force_cache</dt>
                    <dd>強制的にキャッシュを使用する <code>true</code>/<code>false</code></dd>
                </dl>
                <h4>レスポンス</h4>
                <dl>
                    <dt>count</dt>
                    <dd>見つかったツイート数</dd>
                    <dt>max_id</dt>
                    <dd>検索に含めた最新のツイートのID</dd>
                    <dt>query</dt>
                    <dd>検索ワード</dd>
                    <dt>queried</dt>
                    <dd>full/diff/cached</dd>
                    <dt>timestamp</dt>
                    <dd>Cached Time</dd>
                </dl>
                <h4>Example</h4>
                <h5>req</h5>
                <pre>GET https://api.syaroshico.hinaloe.net/api/v1/count.json</pre>
                <h5>res</h5>
                <pre>{"count":1310,"max_id":"673558909290856449","query":"syaroshi.co OR \u30b7\u30e3\u30ed\u30b7\u30b3 exclude:retweets","queried":"cache","timestamp":"1449434847"}</pre>
                <h5>res (expanded)</h5>
                <pre>{
  "count": 1310,
  "max_id": "673558909290856449",
  "query": "syaroshi.co OR シャロシコ exclude:retweets",
  "queried": "cache",
  "timestamp": "1449434847"
}</pre>

            </div>
        </div>
        <h2 class="content-head is-center">About cache</h2>

        <div class="pure-g">
            <div class="l-box-lrg pure-u-1 pure-u-md-3-4 pure-u-lg-5-8">
                カウントは2分間のキャッシュを取っています。キャッシュを使用しない場合、Twitter APIにリクエストを送るためレスポンスに多少のタイムロスが生じます。
            </div>
        </div>
        <h2 class="content-head is-center">Rate limit / エラーについて</h2>

        <div class="pure-g">
            <div class="l-box-lrg pure-u-1 pure-u-md-3-4 pure-u-lg-5-8">
                <p> このAPIには現在レートリミット等の制限は設けていませんが、TwitterのAPIやこのプログラムの内部的エラーが返される場合があります。</p>

                <p>エラーの際は<code>message</code>にエラーメッセージ、<code>error</code>に<code>true</code>を含むJSONが返されます。countにカウントが含まれる場合もありますが、負の値が含まれる可能性があることを考慮してください。(この場合カウントとは関係が無くなります)
                </p>
            </div>
        </div>
        <h2 class="content-head is-center"><i class="fa fa-lock"></i> Force SSL</h2>

        <div class="pure-g">
            <div class="l-box-lrg pure-u-1 pure-u-md-3-4 pure-u-lg-5-8">
                <p>全てのエンドポイントへのアクセスはSSL(TLS)によって保護されます。もし証明書にエラーがある際は<a href="https://twitter.com/hnle0">@hnle0</a>にpingしてください。
                </p>
            </div>
        </div>
        <h2 class="content-head is-center"><i class="fa fa-github"></i> Code on GitHub</h2>

        <div class="pure-g">
            <div class="l-box-lrg pure-u-1 pure-u-md-3-4 pure-u-lg-5-8">
                <p>ソースコードはGitHubにてMITライセンスにて公開しています。
                </p>
                <a href="https://github.com/hinaloe/syaroshico" class="pure-button pure-button-primary"><i
                            class="fa fa-code-fork"></i> Fork me on GitHub</a>
            </div>
        </div>

    </div>
    <div class="footer l-box is-center">
        <p>© hinaloe 2015</p>
    </div>


</div>

<div class="floating-top-button">
    <a href="#"><i class="fa fa-chevron-up"></i></a>
</div>


<style>
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    /*
     * -- BASE STYLES --
     * Most of these are inherited from Base, but I want to change a few.
     */
    body {
        line-height: 1.7em;
        color: #7f8c8d;
        font-size: 13px;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    label {
        color: #34495e;
    }

    .pure-img-responsive {
        max-width: 100%;
        height: auto;
    }

    /*
     * -- LAYOUT STYLES --
     * These are some useful classes which I will need
     */
    .l-box {
        padding: 1em;
    }

    .l-box-lrg {
        padding: 2em;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .is-center {
        text-align: center;
    }

    /*
     * -- PURE FORM STYLES --
     * Style the form inputs and labels
     */
    .pure-form label {
        margin: 1em 0 0;
        font-weight: bold;
        font-size: 100%;
    }

    .pure-form input[type] {
        border: 2px solid #ddd;
        box-shadow: none;
        font-size: 100%;
        width: 100%;
        margin-bottom: 1em;
    }

    /*
     * -- PURE BUTTON STYLES --
     * I want my pure-button elements to look a little different
     */
    .pure-button {
        background-color: #1f8dd6;
        color: white;
        padding: 0.5em 2em;
        border-radius: 5px;
    }

    a.pure-button-primary {
        background: white;
        color: #1f8dd6;
        border-radius: 5px;
        font-size: 120%;
    }

    /*
     * -- MENU STYLES --
     * I want to customize how my .pure-menu looks at the top of the page
     */

    .home-menu {
        padding: 0.5em;
        text-align: center;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.10);
    }

    .home-menu {
        background: #2d3e50;
    }

    .pure-menu.pure-menu-fixed {
        /* Fixed menus normally have a border at the bottom. */
        border-bottom: none;
        /* I need a higher z-index here because of the scroll-over effect. */
        z-index: 4;
    }

    .home-menu .pure-menu-heading {
        color: white;
        font-weight: 400;
        font-size: 120%;
    }

    .home-menu .pure-menu-selected a {
        color: white;
    }

    .home-menu a {
        color: #6FBEF3;
    }

    .home-menu li a:hover,
    .home-menu li a:focus {
        background: none;
        border: none;
        color: #AECFE5;
    }

    /*
     * -- SPLASH STYLES --
     * This is the blue top section that appears on the page.
     */

    .splash-container {
        background: transparent;
        z-index: 1;
        overflow: hidden;
        /* The following styles are required for the "scroll-over" effect */
        width: 100%;
        height: 88%;
        top: 0;
        left: 0;
        position: fixed !important;
    }

    .splash-container.background {
        background: #eee311 url('https://pbs.twimg.com/media/CUsgughUEAAuRp0.jpg:orig');
        -webkit-background-size: cover;
        background-size: cover;
        z-index: 0;
        -webkit-filter: blur(3.5px);
        -moz-filter: blur(3.5px);
        -ms-filter: blur (3.5px);
        -o-filter: blur(3.5px);
        filter: blur(3.5px);
    }

    .splash {
        /* absolute center .splash within .splash-container */
        width: 80%;
        height: 50%;
        margin: auto;
        position: absolute;
        top: 100px;
        left: 0;
        bottom: 0;
        right: 0;
        text-align: center;
        text-transform: uppercase;
    }

    /* This is the main heading that appears on the blue section */
    .splash-head {
        font-size: 20px;
        font-weight: bold;
        color: white;
        border: 3px solid white;
        padding: 1em 1.6em;
        font-weight: 100;
        border-radius: 5px;
        line-height: 1em;
    }

    h1.splash-head {
        background: rgba(139, 142, 125, 0.36);
    }

    /* This is the subheading that appears on the blue section */
    .splash-subhead {
        color: rgba(47, 29, 136, 0.73);
        letter-spacing: 0.05em;
        opacity: 0.8;
    }

    /*
     * -- CONTENT STYLES --
     * This represents the content area (everything below the blue section)
     */
    .content-wrapper {
        /* These styles are required for the "scroll-over" effect */
        position: absolute;
        top: 87%;
        width: 100%;
        min-height: 12%;
        z-index: 2;
        background: white;

    }

    /* This is the class used for the main content headers (<h2>) */
    .content-head {
        font-weight: 400;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin: 2em 0 1em;
    }

    /* This is a modifier class used when the content-head is inside a ribbon */
    .content-head-ribbon {
        color: white;
    }

    /* This is the class used for the content sub-headers (<h3>) */
    .content-subhead {
        color: #1f8dd6;
    }

    .content-subhead i {
        margin-right: 7px;
    }

    /* This is the class used for the dark-background areas. */
    .ribbon {
        background: #2d3e50;
        color: #aaa;
    }

    /* This is the class used for the footer */
    .footer {
        background: #111;
    }

    /*
     * -- TABLET (AND UP) MEDIA QUERIES --
     * On tablets and other medium-sized devices, we want to customize some
     * of the mobile styles.
     */
    @media (min-width: 48em) {

        /* We increase the body font size */
        body {
            font-size: 16px;
        }

        /* We want to give the content area some more padding */
        .content {
            padding: 1em;
        }

        /* We can align the menu header to the left, but float the
        menu items to the right. */
        .home-menu {
            text-align: left;
        }

        .home-menu ul {
            float: right;
        }

        /* We increase the height of the splash-container */
        /*    .splash-container {
                height: 500px;
            }*/
        /* We decrease the width of the .splash, since we have more width
        to work with */
        .splash {
            width: 50%;
            height: 50%;
        }

        .splash-head {
            font-size: 250%;
        }

        /* We remove the border-separator assigned to .l-box-lrg */
        .l-box-lrg {
            border: none;
        }

    }

    /*
     * -- DESKTOP (AND UP) MEDIA QUERIES --
     * On desktops and other large devices, we want to over-ride some
     * of the mobile and tablet styles.
     */
    @media (min-width: 78em) {
        /* We increase the header font size even more */
        .splash-head {
            font-size: 300%;
        }
    }

    .floating-top-button {
        position: fixed;
        width: 40px;
        height: 40px;
        bottom: 20px;
        right: 20px;
        z-index: 100;
        background-color: rgba(128, 131, 123, 0.65);
        border-radius: 50%;
    }

    .floating-top-button a {
        display: block;
        width: 100%;
        height: 100%;
        color: white;

        padding-left: 12px;
        padding-top: 5px;

    }
</style>
</body>
</html>