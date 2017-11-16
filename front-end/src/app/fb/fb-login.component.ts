import {Component, OnInit} from '@angular/core';
import {FacebookService, InitParams, LoginResponse} from 'ngx-facebook';

@Component({
  template: `
    <div></div>`
})
export class FbLoginComponent implements OnInit {

  constructor(private fb: FacebookService) {
    const initParams: InitParams = {
      appId: '513153505703978',
      xfbml: true,
      version: 'v2.8'
    };

    fb.init(initParams);
  }

  ngOnInit() {
    console.log('Entered');
    this.fb.login()
      .then((response: LoginResponse) => console.log(response))
      .catch((error: any) => console.error(error));
  }
}
