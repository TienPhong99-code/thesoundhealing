<?php
/**
 * The template for blog page
 * 
 * @author MONA.Media / Website
 */

use Composer\Installers\PPIInstaller;

if ( ! defined( 'ABSPATH' ) ) {
    die();
}
global $mona_current_permalink;
$page_id = MONA_PAGE_BLOG;

get_header();
echo '<h1 class="hide-sitename">' . get_post_field('post_title', $page_id) . '</h1>';

mona_output_breadcrumb();



?>
      <section class="sec-homes-bn"> 
        <div class="homes-bn">
          <div class="homes-bn__slider">
            <div class="swiper"> 
              <div class="swiper-wrapper"> 
                <div class="swiper-slide">
                  <div class="homes-bn__item">
                    <div class="bg">    <img src="./assets/images/bn-home-1.jpg" alt="" loading="lazy"/>
                    </div>
                    <div class="homes-bn__content"></div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="homes-bn__item">
                    <div class="bg">    <img src="./assets/images/bn-home-2.jpg" alt="" loading="lazy"/>
                    </div>
                    <div class="homes-bn__content"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </section>
      <section class="sec-homes-dmnb"> 
        <div class="homes-dmnb ss-mg"> 
          <div class="container"> 
            <div class="title-gr center mb-24">
              <div class="title title-36 add-class t-medium text-hori" data-spl="data-spl">Danh mục nổi bật</div>
            </div>
            <div class="homes-dmnb__slider checkViewJS fadeUp" data-mirror="false" data-offset="1.2">
              <div class="homes-dmnb__sw relative">
                <div class="swiper row gap-res rows-5"> 
                  <div class="swiper-wrapper"> 
                    <div class="swiper-slide col"> 
                      <div class="item-circle"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-circle1.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Đầm dạo phố  </a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-circle"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-circle2.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Đầm dạo phố  </a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-circle"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-circle3.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Đầm dạo phố  </a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-circle"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-circle4.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Đầm dạo phố  </a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-circle"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-circle1.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Đầm dạo phố  </a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-circle"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-circle2.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Đầm dạo phố  </a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-circle"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-circle3.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Đầm dạo phố  </a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-circle"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-circle4.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Đầm dạo phố  </a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-control posi minus"> 
                  <div class="swiper-control-btn swiper-prev"> <i class="fa-solid fa-arrow-left"></i></div>
                  <div class="swiper-control-btn swiper-next"> <i class="fa-solid fa-arrow-right"></i></div>
                </div>
              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>
        </div>
      </section>
      <section class="sec-homes-dmnb homes-bst">
        <div class="homes-dmnb ss-mg">
          <div class="container"> 
            <div class="title-gr center mb-24">
              <div class="title title-36 add-class t-medium text-hori" data-spl="data-spl">Bộ sưu tập</div>
            </div>
            <div class="homes-dmnb__slider checkViewJS fadeUp" data-mirror="false" data-offset="1.2">
              <div class="homes-dmnb__sw relative">
                <div class="swiper row gap-res rows-3"> 
                  <div class="swiper-wrapper"> 
                    <div class="swiper-slide col"> 
                      <div class="item-bst"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-bst1.jpg" alt="" loading="lazy"/></a>
                            <div class="img-info"> 
                              <div class="iwt"> 
                                <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                                </div>
                                <p class="txt">Nguyễn Thị Kim Ngân</p>
                              </div>
                            </div>
                          </div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-bst"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-bst2.jpg" alt="" loading="lazy"/></a>
                            <div class="img-info"> 
                              <div class="iwt"> 
                                <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                                </div>
                                <p class="txt">Nguyễn Thị Kim Ngân</p>
                              </div>
                            </div>
                          </div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-bst"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-bst3.jpg" alt="" loading="lazy"/></a>
                            <div class="img-info"> 
                              <div class="iwt"> 
                                <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                                </div>
                                <p class="txt">Nguyễn Thị Kim Ngân</p>
                              </div>
                            </div>
                          </div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-bst"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-bst1.jpg" alt="" loading="lazy"/></a>
                            <div class="img-info"> 
                              <div class="iwt"> 
                                <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                                </div>
                                <p class="txt">Nguyễn Thị Kim Ngân</p>
                              </div>
                            </div>
                          </div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-bst"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-bst2.jpg" alt="" loading="lazy"/></a>
                            <div class="img-info"> 
                              <div class="iwt"> 
                                <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                                </div>
                                <p class="txt">Nguyễn Thị Kim Ngân</p>
                              </div>
                            </div>
                          </div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-bst"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/item-bst3.jpg" alt="" loading="lazy"/></a>
                            <div class="img-info"> 
                              <div class="iwt"> 
                                <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                                </div>
                                <p class="txt">Nguyễn Thị Kim Ngân</p>
                              </div>
                            </div>
                          </div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>
        </div>
      </section>
      <section class="sec-homes-spm"> 
        <div class="homes-spm ss-mg"> 
          <div class="container"> 
            <div class="title-gr center mb-24">
              <div class="title title-36 add-class t-medium text-hori" data-spl="data-spl">Sản phẩm mới ra mắt</div>
            </div>
            <div class="homes-spm__swiper changeCursorJS">  
              <div class="swiper-control posi midle"> 
                <div class="swiper-control-btn swiper-prev"> <i class="fa-solid fa-arrow-left"></i></div>
                <div class="swiper-control-btn swiper-next"> <i class="fa-solid fa-arrow-right"></i></div>
              </div>
              <div class="swiper swiper-big"> 
                <div class="swiper-wrapper"> 
                  <div class="swiper-slide"> 
                    <div class="homes-spm__slider">
                      <div class="homes-spm__slider-bg"> <img src="./assets/images/ivory1.png" alt="" loading="lazy"/>
                      </div>
                      <div class="homes-spm__sliderIn">
                        <div class="swiper row"> 
                          <div class="swiper-wrapper"> 
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three1.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three2.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three3.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three1.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three2.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three3.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="homes-spm__slider-dot"> <img src="./assets/images/home-spm-slider-dot.png" alt="" loading="lazy"/>
                      </div>
                      <div class="homes-spm__slider-content"> 
                        <div class="mona-content"> 
                          <p>Đầm xoè chấm bi, cổ nơ nữ tính. Chân váy tầng bồng nhẹ, tôn dáng và dễ mặc.</p>
                        </div>
                        <div class="btn-box"><a class="btn white center" href=""><span class="txt">Xem chi tiết</span></a></div>
                      </div>
                    </div>
                  </div>
                  <div class="swiper-slide"> 
                    <div class="homes-spm__slider">
                      <div class="homes-spm__slider-bg"> <img src="./assets/images/ivory2.png" alt="" loading="lazy"/>
                      </div>
                      <div class="homes-spm__sliderIn">
                        <div class="swiper row"> 
                          <div class="swiper-wrapper"> 
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three1.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three2.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three3.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three1.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three2.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                            <div class="swiper-slide col"> 
                              <div class="img"> 
                                <div class="img-inner"><img src="./assets/images/item-three3.png" alt="" loading="lazy"/>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="homes-spm__slider-dot"> <img src="./assets/images/home-spm-slider-dot.png" alt="" loading="lazy"/>
                      </div>
                      <div class="homes-spm__slider-content"> 
                        <div class="mona-content"> 
                          <p>Đầm xoè chấm bi, cổ nơ nữ tính. Chân váy tầng bồng nhẹ, tôn dáng và dễ mặc.</p>
                        </div>
                        <div class="btn-box"><a class="btn white center" href=""><span class="txt">Xem chi tiết</span></a></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="sec-homes-hint"> 
        <div class="homes-hint ss-mg"> 
          <div class="container"> 
            <div class="title-gr center mb-24">
              <div class="title title-36 add-class t-medium text-hori" data-spl="data-spl">Gợi ý cho nàng</div>
            </div>
            <div class="homes-hint__filter"> <a class="homes-hint__filter-link active" href=""> Tất cả</a><a class="homes-hint__filter-link" href=""> Áo</a><a class="homes-hint__filter-link" href="">Đầm</a><a class="homes-hint__filter-link" href=""> Quần</a><a class="homes-hint__filter-link" href=""> Chân váy</a><a class="homes-hint__filter-link" href=""> Phụ kiện</a></div>
            <div class="homes-hint__list row gap-res rows-4 checkViewJS fadeUp" data-mirror="false" data-offset="1.2"> 
              <div class="col"> 
                <div class="item-prod"> 
                  <div class="inner hover-img-rotate"> 
                    <div class="img"> 
                      <div class="img-tags"> 
                        <div class="img-tag sale">Giảm 4%</div>
                        <div class="img-tag sold">Hết hàng</div>
                      </div><a class="img-inner" href=""><img src="./assets/images/prod1.jpg" alt="" loading="lazy"/></a>
                    </div>
                    <div class="info"> 
                      <div class="info-size"> <span class="info-size__item">S</span><span class="info-size__item">M</span><span class="info-size__item">L</span><span class="info-size__item">XL</span></div>
                      <h4> <a class="info-tt" href=""> PENELOPE DRESS - Đầm Ren Kem Dáng Dài Lệch Vai Thanh Lịch</a></h4>
                      <div class="box-price"> <span class="price">
                          <del aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>620.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></del><ins aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>600.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></ins></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col"> 
                <div class="item-prod"> 
                  <div class="inner hover-img-rotate"> 
                    <div class="img"> 
                      <div class="img-tags"> 
                        <div class="img-tag sale">Giảm 4%</div>
                        <div class="img-tag sold">Hết hàng</div>
                      </div><a class="img-inner" href=""><img src="./assets/images/prod2.jpg" alt="" loading="lazy"/></a>
                    </div>
                    <div class="info"> 
                      <div class="info-size"> <span class="info-size__item">S</span><span class="info-size__item">M</span><span class="info-size__item">L</span><span class="info-size__item">XL</span></div>
                      <h4> <a class="info-tt" href=""> PENELOPE DRESS - Đầm Ren Kem Dáng Dài Lệch Vai Thanh Lịch</a></h4>
                      <div class="box-price"> <span class="price">
                          <del aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>620.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></del><ins aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>600.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></ins></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col"> 
                <div class="item-prod"> 
                  <div class="inner hover-img-rotate"> 
                    <div class="img"> 
                      <div class="img-tags"> 
                        <div class="img-tag sale">Giảm 4%</div>
                        <div class="img-tag sold">Hết hàng</div>
                      </div><a class="img-inner" href=""><img src="./assets/images/prod3.jpg" alt="" loading="lazy"/></a>
                    </div>
                    <div class="info"> 
                      <div class="info-size"> <span class="info-size__item">S</span><span class="info-size__item">M</span><span class="info-size__item">L</span><span class="info-size__item">XL</span></div>
                      <h4> <a class="info-tt" href=""> PENELOPE DRESS - Đầm Ren Kem Dáng Dài Lệch Vai Thanh Lịch</a></h4>
                      <div class="box-price"> <span class="price">
                          <del aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>620.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></del><ins aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>600.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></ins></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col"> 
                <div class="item-prod"> 
                  <div class="inner hover-img-rotate"> 
                    <div class="img"> 
                      <div class="img-tags"> 
                        <div class="img-tag sale">Giảm 4%</div>
                        <div class="img-tag sold">Hết hàng</div>
                      </div><a class="img-inner" href=""><img src="./assets/images/prod4.jpg" alt="" loading="lazy"/></a>
                    </div>
                    <div class="info"> 
                      <div class="info-size"> <span class="info-size__item">S</span><span class="info-size__item">M</span><span class="info-size__item">L</span><span class="info-size__item">XL</span></div>
                      <h4> <a class="info-tt" href=""> PENELOPE DRESS - Đầm Ren Kem Dáng Dài Lệch Vai Thanh Lịch</a></h4>
                      <div class="box-price"> <span class="price">
                          <del aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>620.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></del><ins aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>600.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></ins></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col"> 
                <div class="item-prod"> 
                  <div class="inner hover-img-rotate"> 
                    <div class="img"> 
                      <div class="img-tags"> 
                        <div class="img-tag sale">Giảm 4%</div>
                        <div class="img-tag sold">Hết hàng</div>
                      </div><a class="img-inner" href=""><img src="./assets/images/prod5.jpg" alt="" loading="lazy"/></a>
                    </div>
                    <div class="info"> 
                      <div class="info-size"> <span class="info-size__item">S</span><span class="info-size__item">M</span><span class="info-size__item">L</span><span class="info-size__item">XL</span></div>
                      <h4> <a class="info-tt" href=""> PENELOPE DRESS - Đầm Ren Kem Dáng Dài Lệch Vai Thanh Lịch</a></h4>
                      <div class="box-price"> <span class="price">
                          <del aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>620.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></del><ins aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>600.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></ins></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col"> 
                <div class="item-prod"> 
                  <div class="inner hover-img-rotate"> 
                    <div class="img"> 
                      <div class="img-tags"> 
                        <div class="img-tag sale">Giảm 4%</div>
                        <div class="img-tag sold">Hết hàng</div>
                      </div><a class="img-inner" href=""><img src="./assets/images/prod6.jpg" alt="" loading="lazy"/></a>
                    </div>
                    <div class="info"> 
                      <div class="info-size"> <span class="info-size__item">S</span><span class="info-size__item">M</span><span class="info-size__item">L</span><span class="info-size__item">XL</span></div>
                      <h4> <a class="info-tt" href=""> PENELOPE DRESS - Đầm Ren Kem Dáng Dài Lệch Vai Thanh Lịch</a></h4>
                      <div class="box-price"> <span class="price">
                          <del aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>620.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></del><ins aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>600.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></ins></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col"> 
                <div class="item-prod"> 
                  <div class="inner hover-img-rotate"> 
                    <div class="img"> 
                      <div class="img-tags"> 
                        <div class="img-tag sale">Giảm 4%</div>
                        <div class="img-tag sold">Hết hàng</div>
                      </div><a class="img-inner" href=""><img src="./assets/images/prod7.jpg" alt="" loading="lazy"/></a>
                    </div>
                    <div class="info"> 
                      <div class="info-size"> <span class="info-size__item">S</span><span class="info-size__item">M</span><span class="info-size__item">L</span><span class="info-size__item">XL</span></div>
                      <h4> <a class="info-tt" href=""> PENELOPE DRESS - Đầm Ren Kem Dáng Dài Lệch Vai Thanh Lịch</a></h4>
                      <div class="box-price"> <span class="price">
                          <del aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>620.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></del><ins aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>600.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></ins></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col"> 
                <div class="item-prod"> 
                  <div class="inner hover-img-rotate"> 
                    <div class="img"> 
                      <div class="img-tags"> 
                        <div class="img-tag sale">Giảm 4%</div>
                        <div class="img-tag sold">Hết hàng</div>
                      </div><a class="img-inner" href=""><img src="./assets/images/prod8.jpg" alt="" loading="lazy"/></a>
                    </div>
                    <div class="info"> 
                      <div class="info-size"> <span class="info-size__item">S</span><span class="info-size__item">M</span><span class="info-size__item">L</span><span class="info-size__item">XL</span></div>
                      <h4> <a class="info-tt" href=""> PENELOPE DRESS - Đầm Ren Kem Dáng Dài Lệch Vai Thanh Lịch</a></h4>
                      <div class="box-price"> <span class="price">
                          <del aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>620.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></del><ins aria-hidden="true"><span class="woocommerce-Price-amount amount">
                              <bdi>600.000<span class="woocommerce-Price-currencySymbol">₫</span></bdi></span></ins></span></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="btn-box"><a class="btn white center" href=""><span class="txt">Xem tất cả</span></a></div>
          </div>
        </div>
      </section>
      <section class="sec-homes-fb">
        <div class="homes-fb ss-pd bg-gray"> 
          <div class="container"> 
            <div class="title-gr center mb-24">
              <div class="title title-36 add-class t-medium text-hori" data-spl="data-spl">Feedback khách hàng</div>
            </div>
            <div class="homes-fb__slider checkViewJS fadeUp relative" data-mirror="false" data-offset="1.2">
              <div class="swiper-control posi minus"> 
                <div class="swiper-control-btn swiper-prev"> <i class="fa-solid fa-arrow-left"></i></div>
                <div class="swiper-control-btn swiper-next"> <i class="fa-solid fa-arrow-right"></i></div>
              </div>
              <div class="swiper row rows-3 gap-res"> 
                <div class="swiper-wrapper"> 
                  <div class="swiper-slide col"> 
                    <div class="item-bst no-tt"> 
                      <div class="inner hover-img-rotate"> 
                        <div class="img"> <a class="img-inner" href=""><img src="./assets/images/fb1.jpg" alt="" loading="lazy"/></a>
                          <div class="img-info"> 
                            <div class="iwt"> 
                              <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                              </div>
                              <p class="txt">Nguyễn Thị Kim Ngân</p>
                            </div>
                          </div>
                        </div>
                        <div class="info"> 
                          <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="swiper-slide col"> 
                    <div class="item-bst no-tt"> 
                      <div class="inner hover-img-rotate"> 
                        <div class="img"> <a class="img-inner" href=""><img src="./assets/images/fb2.jpg" alt="" loading="lazy"/></a>
                          <div class="img-info"> 
                            <div class="iwt"> 
                              <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                              </div>
                              <p class="txt">Nguyễn Thị Kim Ngân</p>
                            </div>
                          </div>
                        </div>
                        <div class="info"> 
                          <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="swiper-slide col"> 
                    <div class="item-bst no-tt"> 
                      <div class="inner hover-img-rotate"> 
                        <div class="img"> <a class="img-inner" href=""><img src="./assets/images/fb3.jpg" alt="" loading="lazy"/></a>
                          <div class="img-info"> 
                            <div class="iwt"> 
                              <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                              </div>
                              <p class="txt">Nguyễn Thị Kim Ngân</p>
                            </div>
                          </div>
                        </div>
                        <div class="info"> 
                          <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="swiper-slide col"> 
                    <div class="item-bst no-tt"> 
                      <div class="inner hover-img-rotate"> 
                        <div class="img"> <a class="img-inner" href=""><img src="./assets/images/fb1.jpg" alt="" loading="lazy"/></a>
                          <div class="img-info"> 
                            <div class="iwt"> 
                              <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                              </div>
                              <p class="txt">Nguyễn Thị Kim Ngân</p>
                            </div>
                          </div>
                        </div>
                        <div class="info"> 
                          <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="swiper-slide col"> 
                    <div class="item-bst no-tt"> 
                      <div class="inner hover-img-rotate"> 
                        <div class="img"> <a class="img-inner" href=""><img src="./assets/images/fb2.jpg" alt="" loading="lazy"/></a>
                          <div class="img-info"> 
                            <div class="iwt"> 
                              <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                              </div>
                              <p class="txt">Nguyễn Thị Kim Ngân</p>
                            </div>
                          </div>
                        </div>
                        <div class="info"> 
                          <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="swiper-slide col"> 
                    <div class="item-bst no-tt"> 
                      <div class="inner hover-img-rotate"> 
                        <div class="img"> <a class="img-inner" href=""><img src="./assets/images/fb3.jpg" alt="" loading="lazy"/></a>
                          <div class="img-info"> 
                            <div class="iwt"> 
                              <div class="icon"> <img src="./assets/images/ic-ig.svg" alt="" loading="lazy"/>
                              </div>
                              <p class="txt">Nguyễn Thị Kim Ngân</p>
                            </div>
                          </div>
                        </div>
                        <div class="info"> 
                          <h4><a class="info-tt" href="">RADIANCE COLLECTION</a></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>
        </div>
      </section>
      <section class="sec-homes-seven"> 
        <div class="homes-seven"> 
          <div class="bg imgPara"> <img src="./assets/images/img-seven.jpg" alt="" loading="lazy"/>
          </div>
          <div class="container"> 
            <div class="homes-seven__content"> 
              <div class="head-verti center"> 
                <div class="title title-48 add-class t-medium text-hori t-white" data-spl="data-spl">7 điều chúng tôi cam kết với bạn</div>
                <div class="homes-seven__btn popup-open" data-popup="slider"> <img src="./assets/images/ic-slide.svg" alt="" loading="lazy"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="sec-homes-about"> 
        <div class="homes-about ss-mg">
          <div class="dec"> 
            <div class="dec-in"> <img src="./assets/images/logo-opa.png" alt="" loading="lazy"/>
            </div>
          </div>
          <div class="container"> 
            <div class="homes-about__slider"> 
              <div class="swiper"> 
                <div class="swiper-wrapper"> 
                  <div class="swiper-slide"> 
                    <div class="homes-about__flex row">
                      <div class="homes-about__left col"> 
                        <div class="img"> 
                          <div class="img-inner"><img src="./assets/images/homes-about-img.jpg" alt="" loading="lazy"/>
                          </div>
                        </div>
                      </div>
                      <div class="homes-about__right col"> 
                        <div class="wrapper"> 
                          <div class="title-gr">
                            <div class="title title-36 add-class t-medium text-hori" data-spl="data-spl">Không chỉ là trang phục</div>
                          </div>
                          <div class="mona-content">
                            <p>ANH'S Fashion – thời thượng, tối giản và tinh tế. Một góc nhỏ dành cho “em”, nơi mỗi thiết kế đều sạch sẽ, thanh lịch và dễ mặc hằng ngày. Anh's Fashion đồng hành cùng em trên hành trình trở nên xinh đẹp hơn — nhẹ nhàng, hiện đại và luôn toả sáng theo cách riêng của mình.</p>
                          </div>
                          <div class="homes-about__btns">
                            <div class="btn-box"><a class="btn white" href=""><span class="txt">Tìm hiểu về chúng tôi</span></a></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="swiper-slide"> 
                    <div class="homes-about__flex row">
                      <div class="homes-about__left col"> 
                        <div class="img"> 
                          <div class="img-inner"><img src="./assets/images/homes-about-img.jpg" alt="" loading="lazy"/>
                          </div>
                        </div>
                      </div>
                      <div class="homes-about__right col"> 
                        <div class="wrapper"> 
                          <div class="title-gr">
                            <div class="title title-36 add-class t-medium text-hori" data-spl="data-spl">Không chỉ là trang phục</div>
                          </div>
                          <div class="mona-content">
                            <p>ANH'S Fashion – thời thượng, tối giản và tinh tế. Một góc nhỏ dành cho “em”, nơi mỗi thiết kế đều sạch sẽ, thanh lịch và dễ mặc hằng ngày. Anh's Fashion đồng hành cùng em trên hành trình trở nên xinh đẹp hơn — nhẹ nhàng, hiện đại và luôn toả sáng theo cách riêng của mình.</p>
                          </div>
                          <div class="homes-about__btns">
                            <div class="btn-box"><a class="btn white" href=""><span class="txt">Tìm hiểu về chúng tôi</span></a></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="swiper-slide"> 
                    <div class="homes-about__flex row">
                      <div class="homes-about__left col"> 
                        <div class="img"> 
                          <div class="img-inner"><img src="./assets/images/homes-about-img.jpg" alt="" loading="lazy"/>
                          </div>
                        </div>
                      </div>
                      <div class="homes-about__right col"> 
                        <div class="wrapper"> 
                          <div class="title-gr">
                            <div class="title title-36 add-class t-medium text-hori" data-spl="data-spl">Không chỉ là trang phục</div>
                          </div>
                          <div class="mona-content">
                            <p>ANH'S Fashion – thời thượng, tối giản và tinh tế. Một góc nhỏ dành cho “em”, nơi mỗi thiết kế đều sạch sẽ, thanh lịch và dễ mặc hằng ngày. Anh's Fashion đồng hành cùng em trên hành trình trở nên xinh đẹp hơn — nhẹ nhàng, hiện đại và luôn toả sáng theo cách riêng của mình.</p>
                          </div>
                          <div class="homes-about__btns">
                            <div class="btn-box"><a class="btn white" href=""><span class="txt">Tìm hiểu về chúng tôi</span></a></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="swiper-slide"> 
                    <div class="homes-about__flex row">
                      <div class="homes-about__left col"> 
                        <div class="img"> 
                          <div class="img-inner"><img src="./assets/images/homes-about-img.jpg" alt="" loading="lazy"/>
                          </div>
                        </div>
                      </div>
                      <div class="homes-about__right col"> 
                        <div class="wrapper"> 
                          <div class="title-gr">
                            <div class="title title-36 add-class t-medium text-hori" data-spl="data-spl">Không chỉ là trang phục</div>
                          </div>
                          <div class="mona-content">
                            <p>ANH'S Fashion – thời thượng, tối giản và tinh tế. Một góc nhỏ dành cho “em”, nơi mỗi thiết kế đều sạch sẽ, thanh lịch và dễ mặc hằng ngày. Anh's Fashion đồng hành cùng em trên hành trình trở nên xinh đẹp hơn — nhẹ nhàng, hiện đại và luôn toả sáng theo cách riêng của mình.</p>
                          </div>
                          <div class="homes-about__btns">
                            <div class="btn-box"><a class="btn white" href=""><span class="txt">Tìm hiểu về chúng tôi</span></a></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="homes-about__slider-control"> 
                <div class="swiper-control"> 
                  <div class="swiper-control-btn swiper-prev"><i class="fa-solid fa-chevron-left"></i></div>
                  <div class="swiper-pagination"></div>
                  <div class="swiper-control-btn swiper-next"><i class="fa-solid fa-chevron-right"></i></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="sec-homes-dmnb homes-news">
        <div class="homes-dmnb ss-mg">
          <div class="container"> 
            <div class="title-gr center mb-24">
              <div class="title title-36 add-class t-medium text-hori" data-spl="data-spl">Bộ sưu tập</div>
            </div>
            <div class="homes-dmnb__slider checkViewJS fadeUp" data-mirror="false" data-offset="1.2">
              <div class="homes-dmnb__sw relative">
                <div class="swiper row gap-res rows-3"> 
                  <div class="swiper-wrapper"> 
                    <div class="swiper-slide col"> 
                      <div class="item-bst ver2"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/news1.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Invory không chỉ là váy – đó là cảm giác tự tin, dịu dàng và quyến rũ</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-bst ver2"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/news2.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Invory không chỉ là váy – đó là cảm giác tự tin, dịu dàng và quyến rũ</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-bst ver2"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/news3.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Invory không chỉ là váy – đó là cảm giác tự tin, dịu dàng và quyến rũ</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-bst ver2"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/news1.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Invory không chỉ là váy – đó là cảm giác tự tin, dịu dàng và quyến rũ</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-bst ver2"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/news2.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Invory không chỉ là váy – đó là cảm giác tự tin, dịu dàng và quyến rũ</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-slide col"> 
                      <div class="item-bst ver2"> 
                        <div class="inner hover-img-rotate"> 
                          <div class="img"> <a class="img-inner" href=""><img src="./assets/images/news3.jpg" alt="" loading="lazy"/></a></div>
                          <div class="info"> 
                            <h4><a class="info-tt" href="">Invory không chỉ là váy – đó là cảm giác tự tin, dịu dàng và quyến rũ</a></h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>
        </div>
      </section>
      <section class="sec-homes-feature"> 
        <div class="homes-feature ss-mg"> 
          <div class="container"> 
            <div class="homes-feature__list row gap-res"> 
              <div class="col"> 
                <div class="homes-feature__item"> 
                  <div class="inner"> 
                    <div class="iwt"> 
                      <div class="icon"> <img src="./assets/images/ic-ft1.svg" alt="" loading="lazy"/>
                      </div>
                      <div class="b-gr"> 
                        <p class="tt"> Sản phẩm cao cấp</p>
                        <p class="des">Các sản phẩm được nhập khẩu nguyên chiếc</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col"> 
                <div class="homes-feature__item"> 
                  <div class="inner"> 
                    <div class="iwt"> 
                      <div class="icon"> <img src="./assets/images/ic-ft2.svg" alt="" loading="lazy"/>
                      </div>
                      <div class="b-gr"> 
                        <p class="tt"> Hỗ trợ 24/7</p>
                        <p class="des">CĐội ngũ tư vấn viên luôn hỗ trợ online 24/7</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col"> 
                <div class="homes-feature__item"> 
                  <div class="inner"> 
                    <div class="iwt"> 
                      <div class="icon"> <img src="./assets/images/ic-ft3.svg" alt="" loading="lazy"/>
                      </div>
                      <div class="b-gr"> 
                        <p class="tt"> Vận chuyển toàn quốc</p>
                        <p class="des">Miễn phí nội thành TP. Hà Nội và TP. Hồ Chí Minh</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col"> 
                <div class="homes-feature__item"> 
                  <div class="inner"> 
                    <div class="iwt"> 
                      <div class="icon"> <img src="./assets/images/ic-ft4.svg" alt="" loading="lazy"/>
                      </div>
                      <div class="b-gr"> 
                        <p class="tt">Tư vấn miễn phí</p>
                        <p class="des">Đội ngũ CSKH tư vấn mua hàng tận tâm</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
<?php



get_footer();