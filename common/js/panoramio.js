function showFotos(lat,lng){

  var beaches = {'rect': {'sw': {'lat': (lat-0.045), 'lng': (lng-0.045)}, 'ne': {'lat': (lat+0.045), 'lng': (lng+0.045)}}};
  var photo_ex_options = {'width': 580, 'height': 340, 'columns': 4, rows:2};
  var photo_ex_widget = new panoramio.PhotoListWidget('div_photo_ex', beaches, photo_ex_options);
  photo_ex_widget.setPosition(0);
}