#include <pebble.h>

Window *my_window;
TextLayer *title_text_layer;
TextLayer *body_text_layer;

void handle_init(void) {
  my_window = window_create();
  window_set_background_color(my_window, GColorVividCerulean);  
  window_stack_push(my_window, true);

  title_text_layer = text_layer_create(GRect(0, 50, 144, 40));
  text_layer_set_font(title_text_layer, fonts_get_system_font(FONT_KEY_GOTHIC_28_BOLD));
  text_layer_set_text_alignment(title_text_layer, GTextAlignmentCenter);
  text_layer_set_text_color(title_text_layer, GColorBlack);	
  text_layer_set_text(title_text_layer, "Subscribed!!");
  layer_add_child(window_get_root_layer(my_window), text_layer_get_layer(title_text_layer));	

  body_text_layer = text_layer_create(GRect(0, 80, 144, 40));
  text_layer_set_font(body_text_layer, fonts_get_system_font(FONT_KEY_GOTHIC_24));
  text_layer_set_text_alignment(body_text_layer, GTextAlignmentCenter);
  text_layer_set_text_color(body_text_layer, GColorBlack);	
  text_layer_set_text(body_text_layer, "Timeline pins coming.");
  layer_add_child(window_get_root_layer(my_window), text_layer_get_layer(body_text_layer));	
}

void handle_deinit(void) {
  text_layer_destroy(title_text_layer);
  text_layer_destroy(body_text_layer);
  window_destroy(my_window);
}

int main(void) {
  handle_init();
  app_event_loop();
  handle_deinit();
}
