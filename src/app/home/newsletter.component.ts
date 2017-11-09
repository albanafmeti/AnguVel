import {Component, OnInit} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {NotificationsService} from 'angular2-notifications';
import {Observable} from 'rxjs/Observable';
import {environment} from '../../environments/environment';

@Component({
  selector: 'app-newsletter',
  templateUrl: './newsletter.component.html'
})
export class NewsletterComponent implements OnInit {

  email: string;

  constructor(private _http: HttpClient,
              private notificationsService: NotificationsService) {
  }

  ngOnInit() {
  }

  public subscribe() {
    const request: Observable<any> = this._http.post(environment.apiUrl + 'subscribe', {
      email: this.email,
    });

    request.subscribe(
      response => {
        response = <any>response;
        if (response.success) {
          this.notificationsService.success('Sukses', response.message);
        } else {
          this.notificationsService.warn('Kujdes', response.message);
        }
      },
      errorResponse => {
        if (errorResponse.status !== 422) {
          this.notificationsService.warn('Kujdes', 'Something went wrong.');
        } else {
          const errors = errorResponse.error.errors;
          this.notificationsService.warn('Kujdes', errors[Object.keys(errors)[0]]);
        }
      }
    );
  }
}
