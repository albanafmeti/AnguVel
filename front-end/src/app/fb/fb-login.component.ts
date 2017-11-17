import {Component, OnInit} from '@angular/core';
import {FacebookService, InitParams, LoginResponse, LoginStatus} from 'ngx-facebook';
import {environment} from '../../environments/environment';
import {CookieService} from 'ngx-cookie';
import {ActivatedRoute, Params, Router} from '@angular/router';

@Component({
  template: `
    <div></div>`
})
export class FbLoginComponent implements OnInit {

  constructor(private fb: FacebookService,
              private _cookie: CookieService,
              private activatedRoute: ActivatedRoute,
              private router: Router) {
    const initParams: InitParams = {
      appId: environment.fb_app_id,
      cookie: true,
      xfbml: true,
      version: 'v2.8',
      status: true
    };

    fb.init(initParams);
  }

  ngOnInit() {

    this.activatedRoute.queryParams.subscribe((params: Params) => {
      if (params['appId']) {
        this.fbLogin(params['appId']);
      }
    });

    this.activatedRoute.fragment.subscribe(fragment => {

      if (fragment) {
        const pairs = fragment.split('&');
        const result = {};
        pairs.forEach(function (pair: any) {
          pair = pair.split('=');
          result[pair[0]] = decodeURIComponent(pair[1] || '');
        });

        const hashObject = JSON.parse(JSON.stringify(result));

        if (hashObject.state) {
          this.router.navigateByUrl('/fb/apps/' + hashObject.state);
        }
      }
    });
  }

  fbLogin(appId: string = null) {
    const redirectUri = encodeURI(environment.domainUrl + 'fb/login');
    this.fb.getLoginStatus().then(
      (response: LoginStatus) => {
        if (response.status === 'connected') {

          this._cookie.remove('fb_auth');
          this._cookie.put('fb_auth', JSON.stringify(response.authResponse));

          this.router.navigateByUrl('/fb/apps/' + appId);

        } else {
          window.location.href = encodeURI('https://www.facebook.com/dialog/oauth?client_id=' +
            environment.fb_app_id + '&redirect_uri=' + redirectUri + '&response_type=token' +
            '&scope=email,public_profile,user_birthday,user_friends,publish_actions' +
            '&state=' + appId);
        }
      }
    );
  }
}
