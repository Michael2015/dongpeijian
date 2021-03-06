<?php
$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
$js = <<<EOT
!(function(){ 
    var w =  window,d = document,f = encodeURIComponent;
    var _u = 'https://analytics.goloiov.cn/log.php?';
    function _(a)
    {
        (new Image).src = _u+a.join('&',a); 
    }
    function _c()
    {
        this.l = w.location.href;
        this.h = w.location.host;
        this.ck = '';
        //only id
        this.f = ['u_id','s'];
        //special id
        this.id =  'user_id';
        // url hash
        this.hash  = '';
    }
    _c.prototype = {
        U : function()
        {
            return this.sC(this.l),this.gC(),this.ck;
        },
        H :function(r,q)
        {
            return this.sH(r,q),this.gH(),this.hash;
        },
        gC : function()
        {
            this.user_id = d.cookie.match(/user_id=(\S+);?/) ? d.cookie.match(/user_id=(\S+);?/)[0].replace(';','') : '';
            this.qc = d.cookie.match(/query=(\S*);?/) ? d.cookie.match(/query=(\S*);?/)[0].replace(';','').replace('query=','') : '';
            this.ck = this.user_id+'%26'+this.qc;
        },
        sC : function(l)
        {
            d.cookie = "query=";
            if((pos = l.indexOf('?')) !== -1) 
            {
                var a = l.substr(pos+1).split('&');
                var b = [];
                for(var i = 0;i<a.length;i++)
                {
                    if(a[i])
                    {
                         if(a[i].split('=')[0] === this.id)
                    {
                        d.cookie = this.id+'='+a[i].split('=')[1];
                    }
                    b.push(a[i].split('=')[0]+'='+a[i].split('=')[1]);
                    }
                }
                if(b.length !== 0)
                {
                    d.cookie = "query="+f(b.join('&'));
                }
            }
        },

        gH :function()
        {
             this.hash = d.cookie.match(/hash=(\S+);?/g) ? d.cookie.match(/hash=(\S+);?/g)[0].replace(';','').replace('hash=','') : '';
        },
        sH : function(r,q)
        {
            if(r)
            {
                var m = r.match(/http:\/\/([^\/]+)/i);
                if(m && m[1] != this.h && q.match(/user_id=\S+/))
                {
                   d.cookie = 'hash='+Math.floor(Math.random()*100000000);
                }
            }
        }
    }
    try{
        var c =  new _c;
        var a = [];
        //app_id
        a.push('__app_id='+{$app_id}); 
        //navigator referer
        a.push('__f='+ f(d.referrer));
        //url
        a.push('__l='+f(w.location.href));
        //userAgent
        a.push('__ua='+f(w.navigator.userAgent));
        //rand numbers 
        a.push('__r='+Math.floor(2147483648 * Math.random())); 
        //cookie datas
        a.push('__h='+f(w.location.host));
        //host
        a.push('__c='+f(c.U()));
        //hash 
        a.push('__hash='+f(c.H(d.referrer,w.location.href)));
        //c.H();
        _(a);
    }catch(err){
        console.log(err);
    }
}());
EOT;
echo $js;
?>