import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Observable} from 'rxjs/Observable';
import 'rxjs/add/operator/map';
import {URLSearchParams} from '@angular/http';
import {AuthService} from '../admin/auth/auth.service';
import {environment} from '../../environments/environment';

@Injectable()
export class PostService {
  accessToken: string;
  headers: any;

  constructor(private _http: HttpClient,
              private _auth: AuthService) {
    this.accessToken = this._auth.accessToken();

    this.headers = new HttpHeaders({
      Accept: 'application/json',
      Authorization: 'Bearer ' + this.accessToken,
    });
  }

  getPosts(options?: any): Observable<any> {

    options = options ? options : {};

    if (options.url) {
      return this._http.get(options.url);
    }

    const params = new URLSearchParams();
    if (options.page) {
      params.append('page', String(options.page));
    }

    if (options.perPage) {
      params.append('perPage', String(options.perPage));
    }

    if (options.query) {
      params.append('q', options.query);
    }
    if (options.category_id) {
      params.append('category_id', String(options.category_id));
    }
    const fullQuery = params.toString();

    return this._http.get(environment.apiUrl + 'posts' + ( fullQuery ? ('?' + fullQuery) : ''));
  }

  getPost(slug: string): Observable<any> {
    return this._http.get(environment.apiUrl + 'posts/' + slug);
  }

  latest(limit?: number, options?: any): Observable<any> {

    limit = limit ? limit : 6;
    options = options ? options : {};

    const params = new URLSearchParams();

    if (options.category_id) {
      params.append('category_id', String(options.category_id));
    }

    if (options.featured) {
      params.append('featured', String(options.featured));
    }

    const fullQuery = params.toString();

    return this._http.get(environment.apiUrl + 'posts/latest/' + limit + ( fullQuery ? ('?' + fullQuery) : ''));
  }

  addComment(postSlug: string, author: string, comment: string, email: string): Observable<any> {

    return this._http.post(environment.apiUrl + 'posts/' + postSlug + '/comments/add', {
      'userName': author,
      'userEmail': email,
      'userComment': comment
    });
  }

  getComments(postSlug: string): Observable<any> {

    return this._http.get(environment.apiUrl + 'posts/' + postSlug + '/comments');
  }

  alternativePosts(postSlug: string): Observable<any> {

    return this._http.get(environment.apiUrl + 'posts/' + postSlug + '/alternatives');
  }

  storePost(formData: any): Observable<any> {

    if (this.accessToken) {

      return Observable.create(observer => {

        const xhr: XMLHttpRequest = new XMLHttpRequest();

        xhr.onreadystatechange = () => {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              observer.next(JSON.parse(xhr.response));
              observer.complete();
            } else {
              observer.error({
                status: xhr.status,
                error: JSON.parse(xhr.response)
              });
            }
          }
        };

        xhr.upload.onprogress = (event) => {
          const progress = Math.round(event.loaded / event.total * 100);
          // console.log(progress);
        };

        xhr.open('POST', environment.apiUrl + 'posts/store', true);

        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Authorization', 'Bearer ' + this.accessToken);

        xhr.send(formData);
      });

    } else {
      return Observable.of({
        success: false,
        message: 'Permission denied.'
      });
    }
  }

  updatePost(slug: string, formData: any): Observable<any> {

    if (this.accessToken) {

      return Observable.create(observer => {

        const xhr: XMLHttpRequest = new XMLHttpRequest();

        xhr.onreadystatechange = () => {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              observer.next(JSON.parse(xhr.response));
              observer.complete();
            } else {
              observer.error({
                status: xhr.status,
                error: JSON.parse(xhr.response)
              });
            }
          }
        };

        xhr.upload.onprogress = (event) => {
          const progress = Math.round(event.loaded / event.total * 100);
          // console.log(progress);
        };

        xhr.open('POST', environment.apiUrl + 'posts/' + slug + '/update', true);

        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Authorization', 'Bearer ' + this.accessToken);

        xhr.send(formData);
      });

    } else {
      return Observable.of({
        success: false,
        message: 'Permission denied.'
      });
    }
  }

  deletePost(post: any): Observable<any> {
    if (this.accessToken) {
      return this._http.delete(environment.apiUrl + 'posts/' + post.slug + '/delete', {
        headers: this.headers
      });

    } else {
      return Observable.of({
        success: false,
        message: 'Permission denied.'
      });
    }
  }
}
