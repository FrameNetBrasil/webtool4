 #!/bin/bash
  # save as export_fulltext_parallel.sh

  TOTAL=309
  BATCH_SIZE=50
  PARALLEL_JOBS=4

  NUM_BATCHES=$(( (TOTAL + BATCH_SIZE - 1) / BATCH_SIZE ))

  for ((batch=0; batch<NUM_BATCHES; batch+=PARALLEL_JOBS)); do
      for ((j=0; j<PARALLEL_JOBS && (batch+j)<NUM_BATCHES; j++)); do
          offset=$(( (batch + j) * BATCH_SIZE ))
          php artisan export:xml-framework fulltext --limit=$BATCH_SIZE --offset=$offset --language=1 &
      done
      wait
  done

  echo "Export completed!"
