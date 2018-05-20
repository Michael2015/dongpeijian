//for short code ,lack readable,sorry
//author :yangmingzhao
//date : 2018-05-16
!(function(){ 
    var w =  window,d = document,f = encodeURIComponent;
    var _u = 'http://localhost/dongpeijian/log.php?';
    function _(a)
    {
        (new Image).src = _u+a.join('&',a); 
    }
    function _c()
    {
        this.l = w.location.href;
        this.ck = '';
        //only id
        this.f = ['u_id','s'];
        //special id
        this.id =  'uuuuid';
    }
    _c.prototype = {
        U : function()
        {
            return this.sC(this.l),this.gC(),this.ck;
        },
        gC : function()
        {
            var uuuuid = 
            this.uuuuid = d.cookie.match(/uuuuid=(\S+);?/g) ? d.cookie.match(/uuuuid=(\S+);?/g)[0].replace(';','') : '';
            this.q = d.cookie.match(/query=(.+);?/g) ? d.cookie.match(/query=(.+);?/g)[0].replace(';','').replace('query=','') : '';
            this.ck = this.uuuuid+'%26'+this.q;
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
                    if(a[i].split('=')[0] === this.id)
                    {
                        d.cookie = this.id+'='+a[i].split('=')[1];
                    }
                    b.push(a[i].split('=')[0]+'='+a[i].split('=')[1]);
                }
                if(b.length !== 0)
                {
                    d.cookie = "query="+f(b.join('&'));
                }
            }
        }
    }
    try{
        var c =  new _c;
        var a = [];
        //navigator referer
        a.push('__r='+ f(d.referrer));
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
        _(a);
    }catch(err){
        console.log(err);
    }
}());