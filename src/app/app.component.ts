import {Component} from '@angular/core';
import {PostService} from './services/post.service';
import {CategoryService} from './services/category.service';
import {EmitterService} from './services/emitter.service';

@Component({
  selector: 'app-root',
  template: `
    <div>
      <simple-notifications [options]="notificationOptions"></simple-notifications>
      <app-header></app-header>
      <router-outlet></router-outlet>
      <app-footer></app-footer>
    </div>
  `,
  providers: [PostService, CategoryService, EmitterService]
})
export class AppComponent {
  title = 'app';

  notificationOptions: any = {
    timeOut: 3000
  };

}
