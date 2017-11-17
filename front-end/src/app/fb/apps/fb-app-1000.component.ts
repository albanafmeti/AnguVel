import {Component, Inject, OnInit} from '@angular/core';
import {FacebookService, InitParams, LoginStatus} from 'ngx-facebook';
import {CookieService} from 'ngx-cookie';
import {environment} from '../../../environments/environment';
import {FbAppsService} from '../../services/fb-apps.service';
import {DOCUMENT} from '@angular/common';
import {Router} from '@angular/router';
import {NotificationsService} from 'angular2-notifications';

@Component({
  selector: 'app-fb-app-1000',
  templateUrl: './fb-app-1000.component.html',
  styleUrls: ['./fb-app-1000.component.css']
})
export class FbApp1000Component implements OnInit {

  resultImg: string;
  userName: string;
  currentUrl: string;

  shareText: string;
  shareLink: string;

  disableShare = false;

  constructor(private fb: FacebookService,
              private _cookie: CookieService,
              private _fbApps: FbAppsService,
              @Inject(DOCUMENT) private document: Document,
              private router: Router,
              private notifications: NotificationsService) {

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

    this.fb.getLoginStatus().then(
      (response: LoginStatus) => {
        if (response.status === 'connected') {

          this.getProfile();

        } else {
          this.router.navigateByUrl('/fb/login?appId=1000');
        }
      }
    );
  }

  getProfile() {
    this.fb.api('/me?fields=id,name,picture.type(large)')
      .then((res: any) => {
        // this.currentUrl = this.document.location.href + '?userId=' + res.id;
        this.currentUrl = 'http://terejat.al/fb/apps/1000?userId=' + res.id;
        this.getResult(res);
      })
      .catch(error => console.log(error));
  }

  getResult(data) {
    this._fbApps.appResult_1000(data).subscribe(response => {
        if (response.success) {
          this.userName = response.data.result.user_name;
          this.resultImg = response.data.result.image_url;

          this.shareText = 'Une do te kem pas 10 vitesh ' + response.data.car + '. Zbulojeni edhe ju! :D :D';
          this.shareLink = response.data.link;
        }
      },
      errorReponse => console.log(errorReponse)
    );
  }

  shareFeed() {
    this.fb.api('/me/feed', 'post', {
      message: this.shareText,
      link: this.shareLink
    })
      .then((res: any) => {
        this.disableShare = true;
        this.notifications.success('Urime', 'Ju sapo e shperndate kete ne FACEBOOK.');
      })
      .catch(error => console.log(error));
  }
}
