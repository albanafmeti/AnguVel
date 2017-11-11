import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {NotificationsService} from 'angular2-notifications';
import {CategoryService} from '../../services/category.service';
import {PostService} from '../../services/post.service';
import {SlimLoadingBarService} from 'ng2-slim-loading-bar';
import {environment} from '../../../environments/environment';
import {FroalaService} from '../../services/froala.service';

@Component({
  selector: 'app-add-post',
  templateUrl: './add-post.component.html'
})
export class AddPostComponent implements OnInit {

  title: string;
  slug: string;
  small_content: string;
  content: string;
  author = 'Admin';
  enabled = true;
  featured = false;

  croppedImage: any;

  formData: any;

  categories = [];
  selectedCategories = [];

  slimOptions = {
    download: false,
    minSize: '750,500',
    instantEdit: true,
    rotate: true,
    didSave: this.saveImage.bind(this)
  };

  public editorOptions: Object;

  constructor(private _postService: PostService,
              private _categoryService: CategoryService,
              private notifications: NotificationsService,
              private router: Router,
              private _loadBar: SlimLoadingBarService,
              private _froala: FroalaService) {

    this._loadBar.color = '#ef5285';
  }

  ngOnInit() {

    this.editorOptions = {
      zIndex: 20000,
      placeholderText: 'Insert content of the post.',
      heightMin: 400,
      imageUploadURL: environment.apiUrl + 'froala/image/upload',
      fileUploadURL: environment.apiUrl + 'froala/file/upload',
      imageManagerLoadURL: environment.apiUrl + 'froala/image/manager/load',
      imageManagerDeleteURL: environment.apiUrl + 'froala/image/manager/delete',
      events: {
        'froalaEditor.image.removed': function (e, editor, $img) {
          console.log('removed');
          console.log(this._froala);
          this._froala.deleteImage($img.attr('src')).subscribe(response => {
            console.log(response);
          });
        }.bind(this)
      }
    };

    this._categoryService.getCategories().subscribe(
      response => {

        const categories = [];
        for (const category of response.data) {
          categories.push({
            id: category.id,
            text: category.name
          });
        }
        this.categories = categories;
      },
      error => console.log(<any>error)
    );
  }

  onInput() {
    this.slug = this.title.toLowerCase().replace(/ +/g, '-').replace(/[^a-zA-Z0-9-_]/g, '');
  }

  createPost() {

    this.formData = this.formData ? this.formData : new FormData();

    this.formData.append('title', this.title);
    this.formData.append('slug', this.slug);
    this.formData.append('small_content', this.small_content);
    this.formData.append('content', this.content);
    this.formData.append('author', this.author);
    this.formData.append('enabled', this.enabled ? 1 : 0);
    this.formData.append('featured', this.featured ? 1 : 0);
    this.formData.append('croppedImage', this.croppedImage);

    if (this.selectedCategories.length) {
      this.formData.append('categories', JSON.stringify(this.selectedCategories));
    }

    this._loadBar.start();
    this._postService.storePost(this.formData).subscribe(response => {
      this._loadBar.complete();
      if (response.success) {

        this.notifications.success('Success', response.message);

        // Redirect to posts:
        this.router.navigate(['/admin/posts']);
        return true;
      }
      return this.notifications.error('Error', response.message);
    }, errorResponse => {
      this._loadBar.complete();
      if (errorResponse.status !== 422) {
        return this.notifications.warn('Warning', 'Something went wrong.');
      } else {
        const errors = errorResponse.error.errors;
        return this.notifications.warn('Warning', errors[Object.keys(errors)[0]]);
      }
    });
  }

  onFileChange(event) {
    const fileList: FileList = event.target.files;

    if (fileList.length > 0) {
      const file: File = fileList[0];
      this.formData = new FormData();
      this.formData.append('image', file);
    } else {
      this.formData = null;
    }
  }

  saveImage(object) {
    this.croppedImage = JSON.stringify(object);
  }

  onCategoriesChanged(selectedCategories) {
    this.selectedCategories = [];
    for (const selected of selectedCategories) {
      this.selectedCategories.push(selected.id);
    }
  }
}
